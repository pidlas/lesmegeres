# test-debug.py
import re

with open("style.css", "r", encoding="utf-8") as f:
    css = f.read()

# On cherche TOUTES les lignes qui parlent de la navbar ou du menu pour voir qui bloque
print("=== CONTENU GLOBAL DE .navbar ===")
for ligne in css.splitlines():
    if ".navbar" in ligne or ".menu" in ligne or "justify-content" in ligne:
        print(ligne)
