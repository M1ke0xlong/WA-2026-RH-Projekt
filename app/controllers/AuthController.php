<?php
class AuthController extends Controller { 
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'] ?? '';

            // 1. Základní kontrola vyplnění
            if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
                $error = "Všechna pole musí být vyplněna.";
            } 
            // 2. Kontrola shody hesel
            elseif ($password !== $password_confirm) {
                $error = "Zadaná hesla se neshodují.";
            } 
            // 3. NOVÉ: Vynucení bezpečného hesla (Regex kontrola)
            elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $error = "Heslo musí mít alespoň 8 znaků, obsahovat velké a malé písmeno a číslici.";
            } 
            // 4. Kontrola dostupnosti emailu a jména
            elseif ($userModel->getByEmail($email)) {
                $error = "Tento e-mail už je zaregistrovaný.";
            } 
            // 5. Uložení do databáze (s bezpečným zahašováním)
            else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                if ($userModel->register($username, $email, $hashed_password)) {
                    $this->setToast('Registrace proběhla úspěšně! Nyní se můžeš přihlásit.', 'success');
                    header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                    exit;
                } else {
                    $error = "Něco se pokazilo. Zkus to prosím znovu.";
                }
            }

            // Pokud došlo k chybě, vrátíme uživatele na formulář s chybou
            $this->view('auth/register', ['error' => $error]);
        } else {
            $this->view('auth/register');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $userModel->login($username, $password);
            
            if ($user) {
                // Uložíme data do session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_role'] = $user['role']; // 'user' nebo 'admin'
                
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->view('auth/login', ['error' => 'Špatné jméno nebo heslo.']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}