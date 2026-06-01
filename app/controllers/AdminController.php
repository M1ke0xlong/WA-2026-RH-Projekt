<?php
class AdminController extends Controller {
    
    // Ochrana před neoprávněným přístupem
    
    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdmin();
        $animeModel = $this->model('Anime');
        $animes = $animeModel->getAll();

        $this->view('admin/dashboard', ['animes' => $animes]);
    }

    public function delete($id = null) {
        $this->checkAdmin();
        if ($id) {
            $animeModel = $this->model('Anime');
            $anime = $animeModel->getById($id);
            
            if ($anime) {
                // Fyzické smazání obrázku ze serveru
                if (!empty($anime['image']) && file_exists('../public/uploads/covers/' . $anime['image'])) {
                    unlink('../public/uploads/covers/' . $anime['image']);
                }
                // Smazání z databáze
                $animeModel->delete($id);
            }
        }
        header('Location: ' . BASE_URL . '/index.php?url=admin/dashboard');
        exit;
    }
}