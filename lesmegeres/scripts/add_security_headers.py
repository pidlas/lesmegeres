#!/usr/bin/env python3
"""
Ajoute les 4 headers de sécurité HTTP dans le bloc location principal
de /etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf
Idempotent : ne modifie rien si les headers sont déjà présents.
"""

import shutil
import subprocess
import sys

CONF = "/etc/nginx/conf.d/lesmegeresdelhumus.fr.d/my_webapp.conf"
BACKUP = CONF + ".bak"

HEADERS = (
    '    add_header X-Frame-Options          "SAMEORIGIN"                       always;\n'
    '    add_header X-Content-Type-Options   "nosniff"                          always;\n'
    '    add_header Referrer-Policy          "strict-origin-when-cross-origin"  always;\n'
    "    add_header Content-Security-Policy  \"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; media-src 'self'; object-src 'none'\" always;\n"
)

with open(CONF, "r") as f:
    content = f.read()

# Idempotence
if "X-Frame-Options" in content:
    print("Headers déjà présents — rien à faire.")
    sys.exit(0)

# Trouver le premier bloc "location" (ex: "location / {" ou "location ^~ /")
import re
match = re.search(r'([ \t]*location\b[^\{]*\{)', content)
if not match:
    print("ERREUR : aucun bloc 'location' trouvé dans le fichier.", file=sys.stderr)
    sys.exit(1)

insert_pos = match.end()  # juste après le "{"
new_content = content[:insert_pos] + "\n" + HEADERS + content[insert_pos:]

# Backup
shutil.copy2(CONF, BACKUP)
print(f"Backup créé : {BACKUP}")

with open(CONF, "w") as f:
    f.write(new_content)

print("Headers ajoutés. Test nginx...")
result = subprocess.run(["nginx", "-t"], capture_output=True, text=True)
print(result.stderr)

if result.returncode != 0:
    # Rollback
    shutil.copy2(BACKUP, CONF)
    print("ERREUR nginx -t — rollback effectué.", file=sys.stderr)
    sys.exit(1)

subprocess.run(["systemctl", "reload", "nginx"])
print("OK — nginx rechargé.")
