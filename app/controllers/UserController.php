<?php
class UserController extends Controller {
    
    // Administrátorská správa uživatelů
    public function index() {
        // Zabezpečení: Jen pro adminy
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->setToast('K této stránce má přístup pouze administrátor.', 'error');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $userModel = $this->model('User');
        $users = $userModel->getAllUsers();

        $this->view('user/admin_list', ['users' => $users]);
        
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $userModel = $this->model('User');
        // Přidán nový model pro osobní seznam
        $userListModel = $this->model('UserList'); 
        
        $error = '';
        $success = '';

        // Zpracování avatara... (zůstává stejné)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
            // ... kód pro nahrání avatara (beze změn) ...
            if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                $fileMimeType = mime_content_type($_FILES['avatar']['tmp_name']);

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $avatarName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
                    $targetPath = '../public/uploads/avatars/' . $avatarName;

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                        $userModel->updateAvatar($_SESSION['user_id'], $avatarName);
                        // Použijeme náš toast systém
                        $this->setToast("Avatar byl úspěšně nahrán!", 'success'); 
                    } else { $error = "Chyba při ukládání souboru."; }
                } else { $error = "Nepodporovaný formát. Použij JPG, PNG, WEBP nebo GIF."; }
            }
        }

        // Načteme uživatele (zůstává stejné)
        $user = $userModel->getById($_SESSION['user_id']);

        // NOVÉ: Načteme kompletní seznam anime uživatele
        $animeList = $userListModel->getUserListEntries($_SESSION['user_id']);

        // Předáme data do view
        $this->view('user/profile', [
            'user' => $user,
            'animeList' => $animeList, // <--- Přidán seznam anime
            'error' => $error,
            'success' => $success
        ]);
    }
    // Metoda pro smazání uživatele (přístupná jen adminům)
    public function delete($id = null) {
        // 1. BEZPEČNOSTNÍ KONTROLA: Je uživatel přihlášený a je to admin?
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->setToast('K této akci má přístup pouze administrátor.', 'error');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // 2. KONTROLA ID: Nepokouší se admin smazat sám sebe?
        if ($id) {
            if ($id == $_SESSION['user_id']) {
                $this->setToast('Nemůžeš smazat svůj vlastní administrátorský účet.', 'error');
            } else {
                $userModel = $this->model('User');
                
                // Zkontrolujeme, jestli takový uživatel vůbec existuje
                if ($userModel->getById($id)) {
                    $userModel->deleteUser($id);
                    $this->setToast('Uživatel byl úspěšně smazán z databáze.', 'success');
                } else {
                    $this->setToast('Hledaný uživatel neexistuje.', 'error');
                }
            }
        }

        // 3. PŘESMĚROVÁNÍ ZPĚT
        // Sem doplň URL podle toho, kde se adminům vypisuje seznam uživatelů
        header('Location: ' . BASE_URL . '/index.php'); 
        exit;
    }
}