# Guide SSH - Accès Interne et Externe

Public cible: administrateur technique
Quand utiliser ce document: configurer et verifier les acces SSH local/externe
Prerequis: cle SSH fonctionnelle, acces routeur/firewall, utilisateur serveur actif
Temps de lecture: 8 a 12 min

**Date:** 20 mai 2026  
**Serveur:** Les Mégères de l'Humus  
**Port SSH:** 1000

---

## Vue d'ensemble

Vous avez actuellement une connexion SSH fonctionnelle sur le **réseau local** avec :
- **Adresse :** `192.168.1.100` (IP privée)
- **Port :** `1000`
- **Authentification :** Clé SSH

Cette documentation explique comment **réutiliser la même clé SSH** pour accéder au serveur de **l'extérieur** avec l'adresse publique.

---

## Concept cle: les cles SSH sont independantes de l'adresse/port

### Faux
```
"Je dois créer une nouvelle clé pour une nouvelle adresse IP"
```

### Vrai
```
Une même clé SSH fonctionne avec :
- N'importe quelle adresse IP (privée ou publique)
- N'importe quel port
- N'importe quel serveur (du moment que la clé publique y est présente)
```

---

## Acces interne (reseau local)

### Commande actuelle
```bash
ssh -i ~/.ssh/ma_cle user@192.168.1.100 -p 1000
```

### Detail des parametres
| Paramètre        | Valeur          | Rôle                              |
|------------------|-----------------|-----------------------------------|
| `-i`             | `~/.ssh/ma_cle` | Chemin de la clé privée           |
| `user`           | Votre login SSH | Compte utilisateur sur le serveur |
| `@192.168.1.100` | IP privée       | Adresse locale du serveur         |
| `-p 1000`        | Port 1000       | Port SSH personnalisé             |

### Configuration dans `~/.ssh/config`
Pour simplifier, créez/modifiez `~/.ssh/config` :

```bash
Host megeres-local
    HostName 192.168.1.100
    Port 1000
    User user
    IdentityFile ~/.ssh/ma_cle
    IdentitiesOnly yes
```

Puis connectez-vous simplement :
```bash
ssh megeres-local
```

---

## Acces externe (hors du reseau)

### Commande
```bash
ssh -i ~/.ssh/ma_cle user@IP_PUBLIQUE -p 1000
```

### Exemple
```bash
ssh -i ~/.ssh/ma_cle user@203.0.113.45 -p 1000
```

### Configuration dans `~/.ssh/config`
```bash
Host megeres-public
    HostName 203.0.113.45
    Port 1000
    User user
    IdentityFile ~/.ssh/ma_cle
    IdentitiesOnly yes
```

Puis :
```bash
ssh megeres-public
```

---

## Checklist: avant d'acceder de l'exterieur

### 1) Verifier que la cle publique est sur le serveur

**Sur le serveur (connexion locale) :**
```bash
cat ~/.ssh/authorized_keys
```

Vous devriez voir une ou plusieurs lignes commençant par `ssh-rsa` ou `ssh-ed25519`.

**Votre clé publique doit être présente :**
```bash
# Voir votre clé publique locale
cat ~/.ssh/ma_cle.pub
```

**Si la clé publique n'y est pas, l'ajouter :**
```bash
# Sur votre machine locale
cat ~/.ssh/ma_cle.pub | ssh -p 1000 user@192.168.1.100 "cat >> ~/.ssh/authorized_keys"
```

---

### 2) Verifier que SSH ecoute sur le port 1000

**Sur le serveur :**
```bash
# Méthode 1 (netstat)
sudo netstat -tlnp | grep ssh

# Méthode 2 (ss - plus moderne)
sudo ss -tlnp | grep ssh

# Méthode 3 (ps)
ps aux | grep sshd
```

**Output attendu :**
```
tcp    0    0 0.0.0.0:1000    0.0.0.0:*    LISTEN    1234/sshd
```

**Si le port est incorrect, le modifier dans :**
```bash
# Sur le serveur
sudo nano /etc/ssh/sshd_config
```

Cherchez la ligne `Port` et modifiez-la :
```
Port 1000
```

Puis redémarrez SSH :
```bash
sudo systemctl restart ssh
# ou
sudo service ssh restart
```

---

### 3) Verifier que le port 1000 est accessible de l'exterieur

#### Test depuis votre machine locale
```bash
# Vérifier la connectivité au port
telnet IP_PUBLIQUE 1000
# ou
nc -zv IP_PUBLIQUE 1000
```

#### Si vous etes derriere un routeur domestique

**Port Forwarding requis :**
- Accédez à l'interface de votre routeur (usuellement `192.168.1.1`)
- Allez dans les paramètres **Port Forwarding** ou **UPnP**
- Configurez :
  - **Port externe :** `1000` (ou autre port public)
  - **Port interne :** `1000`
  - **IP interne :** `192.168.1.100`
  - **Protocole :** TCP

---

### 4) Trouver votre adresse IP publique

```bash
# Depuis votre serveur
curl ifconfig.me
# ou
curl icanhazip.com

# Depuis n'importe où
curl -s https://api.ipify.org
```

---

## Tester la connexion externe

### Premier test: depuis le reseau local
```bash
ssh megeres-local
# Devrait fonctionner ✅
```

### Second test: depuis l'exterieur
```bash
ssh megeres-public
# Devrait fonctionner ✅
```

### Depannage si echec
```bash
# Voir les logs de connexion détaillés
ssh -vvv megeres-public

# Sur le serveur, vérifier les logs SSH
sudo journalctl -u ssh -n 50
# ou
sudo tail -50 /var/log/auth.log
```

---

## Recommandations de securite

### Pour l'acces public

#### 1) Port SSH non-standard
Votre port `1000` est déjà bon (pas le standard 22).

#### 2) Authentification par cle uniquement
Désactivez l'authentification par mot de passe sur le serveur :

```bash
sudo nano /etc/ssh/sshd_config
```

Recherchez et modifiez :
```bash
PasswordAuthentication no
PubkeyAuthentication yes
PermitEmptyPasswords no
```

Puis redémarrez :
```bash
sudo systemctl restart ssh
```

#### 3) Restreindre les acces (optionnel)
Si possible, permettre SSH uniquement depuis certaines IPs :

```bash
# Dans /etc/ssh/sshd_config
AllowUsers user@192.168.1.* user@203.0.113.*
```

#### 4) Fail2ban (protection contre le brute-force)
```bash
# Installation
sudo apt-get install fail2ban

# Configuration pour SSH sur port 1000
sudo nano /etc/fail2ban/jail.local
```

Ajoutez :
```
[sshd]
port = 1000
enabled = true
maxretry = 3
findtime = 3600
bantime = 3600
```

Redémarrez :
```bash
sudo systemctl restart fail2ban
```

---

## Resume des fichiers concernes

| Fichier | Localisation | Rôle |
|---------|-------------|------|
| `ma_cle` | `~/.ssh/ma_cle` | Clé privée (sur votre machine) |
| `ma_cle.pub` | `~/.ssh/ma_cle.pub` | Clé publique (optionnel, pour info) |
| `authorized_keys` | `~/.ssh/authorized_keys` (serveur) | Liste des clés autorisées |
| `sshd_config` | `/etc/ssh/sshd_config` (serveur) | Configuration SSH |
| `config` | `~/.ssh/config` (votre machine) | Raccourcis de connexion |

---

## Commandes rapides (copier-coller)

### Test connectivite interne
```bash
ssh -i ~/.ssh/ma_cle user@192.168.1.100 -p 1000 "echo Connection OK"
```

### Test connectivite externe
```bash
ssh -i ~/.ssh/ma_cle user@IP_PUBLIQUE -p 1000 "echo Connection OK"
```

### Transferer des fichiers en local
```bash
scp -i ~/.ssh/ma_cle -P 1000 fichier.txt user@192.168.1.100:/home/user/
```

### Transferer des fichiers en externe
```bash
scp -i ~/.ssh/ma_cle -P 1000 fichier.txt user@IP_PUBLIQUE:/home/user/
```

---

## References

- [SSH Official Documentation](https://man.openbsd.org/ssh)
- [SSH Config Manual](https://man.openbsd.org/ssh_config)
- [Fail2Ban](https://www.fail2ban.org/)
- [RFC 4251 - SSH Protocol](https://tools.ietf.org/html/rfc4251)

---

**Dernière mise à jour:** 20 mai 2026  
**Statut:** Documentation complète ✅  
**Prochaines étapes:** Tester l'accès externe une fois le port forwarding configuré
