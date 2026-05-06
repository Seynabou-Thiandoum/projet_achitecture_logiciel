"""
Application client - Gestion des utilisateurs via le service web SOAP.
Necessite : pip install zeep
"""
import json
import sys
from getpass import getpass
from zeep import Client
from zeep.exceptions import Fault

WSDL_URL = "http://localhost/Projet_architecture_logicielle_diop/services-web/soap/service.wsdl"


class UserManagerApp:
    def __init__(self, wsdl_url: str):
        try:
            self.client = Client(wsdl_url)
        except Exception as exc:
            sys.exit(f"Impossible de se connecter au service SOAP : {exc}")
        self.token: str | None = None
        self.current_user: dict | None = None

    def _call(self, op_name: str, *args):
        """Invoque une operation SOAP et decode le JSON si la reponse est une chaine JSON."""
        op = getattr(self.client.service, op_name)
        result = op(*args)
        if isinstance(result, str):
            try:
                return json.loads(result)
            except json.JSONDecodeError:
                return result
        return result

    # --- Authentification ---
    def login(self) -> bool:
        print("\n=== Connexion ===")
        login = input("Login    : ").strip()
        password = getpass("Mot de passe : ").strip()
        try:
            result = self._call("authenticate", login, password)
        except Fault as e:
            print(f"Erreur SOAP : {e}")
            return False

        if not result.get("success"):
            print(f"Echec : {result.get('message')}")
            return False

        if result.get("role") != "admin":
            print("Vous n'avez pas les droits d'administration.")
            return False

        self.current_user = dict(result)
        print(f"Bienvenue, {login} (role : admin).")

        self.token = input("Veuillez coller votre jeton d'authentification : ").strip()
        if not self.token:
            print("Token requis pour acceder aux operations.")
            return False
        return True

    # --- Operations ---
    def list_users(self) -> None:
        try:
            users = self._call("listUsers", self.token)
        except Fault as e:
            print(f"Erreur : {e}")
            return
        print("\n=== Liste des utilisateurs ===")
        print(f"{'ID':<4}{'LOGIN':<15}{'NOM':<20}{'EMAIL':<25}{'ROLE':<10}")
        print("-" * 74)
        for u in users:
            full = f"{u['prenom']} {u['nom']}"
            print(f"{u['id']:<4}{u['login']:<15}{full:<20}{u['email']:<25}{u['role']:<10}")

    def add_user(self) -> None:
        print("\n=== Ajout d'un utilisateur ===")
        data = {
            "login":    input("Login    : ").strip(),
            "password": getpass("Mot de passe : ").strip(),
            "nom":      input("Nom      : ").strip(),
            "prenom":   input("Prenom   : ").strip(),
            "email":    input("Email    : ").strip(),
            "role":     input("Role (visiteur/editeur/admin) : ").strip() or "visiteur",
        }
        try:
            new_id = self._call(
                "addUser", self.token, data["login"], data["password"],
                data["nom"], data["prenom"], data["email"], data["role"],
            )
            print(f"Utilisateur cree (ID = {new_id}).")
        except Fault as e:
            print(f"Erreur : {e}")

    def update_user(self) -> None:
        print("\n=== Modification d'un utilisateur ===")
        try:
            uid = int(input("ID a modifier : "))
            user = self._call("getUser", self.token, uid)
        except (ValueError, Fault) as e:
            print(f"Erreur : {e}")
            return

        login    = input(f"Login    [{user['login']}] : ").strip()    or user["login"]
        nom      = input(f"Nom      [{user['nom']}] : ").strip()      or user["nom"]
        prenom   = input(f"Prenom   [{user['prenom']}] : ").strip()   or user["prenom"]
        email    = input(f"Email    [{user['email']}] : ").strip()    or user["email"]
        role     = input(f"Role     [{user['role']}] : ").strip()     or user["role"]
        password = getpass("Nouveau mot de passe (ENTER = inchange) : ").strip()

        try:
            ok = self._call(
                "updateUser", self.token, uid, login, nom, prenom, email, role, password,
            )
            print("Modification reussie." if ok else "Echec de la modification.")
        except Fault as e:
            print(f"Erreur : {e}")

    def delete_user(self) -> None:
        print("\n=== Suppression d'un utilisateur ===")
        try:
            uid = int(input("ID a supprimer : "))
        except ValueError:
            print("ID invalide.")
            return
        if input(f"Confirmer la suppression de l'utilisateur {uid} ? (oui/non) : ").lower() != "oui":
            print("Annule.")
            return
        try:
            ok = self._call("deleteUser", self.token, uid)
            print("Suppression reussie." if ok else "Echec.")
        except Fault as e:
            print(f"Erreur : {e}")

    # --- Menu ---
    def menu(self) -> None:
        actions = {
            "1": ("Lister les utilisateurs", self.list_users),
            "2": ("Ajouter un utilisateur",  self.add_user),
            "3": ("Modifier un utilisateur", self.update_user),
            "4": ("Supprimer un utilisateur",self.delete_user),
            "0": ("Quitter",                 None),
        }
        while True:
            print("\n========= MENU =========")
            for k, (label, _) in actions.items():
                print(f"  {k}. {label}")
            choice = input("Choix : ").strip()
            if choice == "0":
                print("Au revoir.")
                return
            action = actions.get(choice)
            if action is None:
                print("Choix invalide.")
                continue
            action[1]()


def main() -> None:
    print("====================================")
    print("  Application de gestion utilisateurs")
    print("====================================")
    app = UserManagerApp(WSDL_URL)
    if app.login():
        app.menu()


if __name__ == "__main__":
    main()
