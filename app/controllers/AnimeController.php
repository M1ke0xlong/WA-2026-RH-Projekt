<?php
class AnimeController extends Controller {
    
    public function index() {
        $animeModel = $this->model('Anime');
        
        // Zjistíme, zda uživatel něco vyhledává
        $searchQuery = trim($_GET['q'] ?? '');
        
        // Získáme anime (buď všechna, nebo vyfiltrovaná)
        $animes = $animeModel->getAll($searchQuery);
        
        $this->view('anime/list', [
            'animes' => $animes,
            'searchQuery' => $searchQuery // Posíláme zpět do šablony, aby zůstal text v políčku
        ]);
    }

    public function create() {
        // Pokud byl formulář odeslán metodou POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $animeModel = $this->model('Anime');
            $error = null;
            
            // 1. Zpracování hlavního obrázku (Cover Image)
            $imageName = null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $fileMimeType = mime_content_type($_FILES['cover_image']['tmp_name']);

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                    $imageName = uniqid('cover_') . '.' . $extension;
                    $targetPath = '../public/uploads/covers/' . $imageName;
                    
                    move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath);
                } else {
                    $error = "Nepodporovaný formát obrázku. Použij JPG, PNG nebo WEBP.";
                }
            }

            // 2. Posbíráme data z formuláře pro hlavní tabulku
            $data = [
                'primary_title'  => trim($_POST['primary_title'] ?? ''),
                'title_english'  => trim($_POST['title_english'] ?? ''),
                'title_japanese' => trim($_POST['title_japanese'] ?? ''),
                'title_romaji'   => trim($_POST['title_romaji'] ?? ''),
                'description'    => trim($_POST['description'] ?? ''),
                'episodes'       => !empty($_POST['episodes']) ? (int)$_POST['episodes'] : null,
                'image'          => $imageName
            ];

            // 3. Hlavní uložení (nejprve vytvoříme anime, pak řešíme galerii)
            if (empty($error)) { // Pokračujeme, jen pokud nebyl problém s obálkou
                if (!empty($data['primary_title'])) {
                    
                    // ZDE zachytíme to nové ID od modelu!
                    $anime_id = $animeModel->create($data);

                    if ($anime_id) { // Pokud se anime úspěšně vytvořilo
                        
                        // 4. Zpracování galerie - Tady už máme $anime_id k dispozici!
                        if (!empty($_FILES['gallery']['name'][0])) {
                            foreach ($_FILES['gallery']['tmp_name'] as $key => $tmpName) {
                                if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                                    $extension = pathinfo($_FILES['gallery']['name'][$key], PATHINFO_EXTENSION);
                                    $fileName = 'gal_' . uniqid() . '.' . $extension;
                                    move_uploaded_file($tmpName, '../public/uploads/gallery/' . $fileName);
                                    
                                    // Uložíme do DB
                                    $animeModel->addGalleryImage($anime_id, $fileName);
                                }
                            }
                        }

                        // Vše je hotovo, zavoláme toast a přesměrujeme
                        $this->setToast('Anime bylo úspěšně přidáno!', 'success');
                        header('Location: ' . BASE_URL . '/index.php');
                        exit;
                        
                    } else {
                        $error = "Došlo k chybě při ukládání do databáze.";
                    }
                } else {
                    $error = "Hlavní název (Primary Title) je povinný!";
                }
            }
            
            // Pokud nastala chyba, zobrazíme formulář znovu s daty
            $this->view('anime/create', ['error' => $error, 'data' => $data]);
            
        } else {
            // GET request - prázdný formulář
            $this->view('anime/create');
        }
    }
    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $animeModel = $this->model('Anime');
        $userListModel = $this->model('UserList');

        // --- 1. ZPRACOVÁNÍ FORMULÁŘŮ (Zde probíhají přesměrování, proto musí být nahoře) ---
        
        // A) Zpracování komentáře
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_content']) && isset($_SESSION['user_id'])) {
            $content = trim($_POST['comment_content']);
            if (!empty($content)) {
                $animeModel->addComment($id, $_SESSION['user_id'], $content);
                $this->setToast('Komentář byl přidán.', 'success');
                header('Location: ' . BASE_URL . '/index.php?url=anime/detail/' . $id);
                exit;
            }
        }

        // B) Zpracování osobního seznamu
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_list' && isset($_SESSION['user_id'])) {
            $status = $_POST['status'];
            $score = $_POST['score'];
            
            if ($status === 'plan_to_watch') {
                $score = null;
            }
            
            $validStatuses = ['watching', 'completed', 'on_hold', 'dropped', 'plan_to_watch'];
            if (in_array($status, $validStatuses)) {
                $userListModel->saveEntry($_SESSION['user_id'], $id, $status, $score);
                $this->setToast('Tvůj seznam byl úspěšně aktualizován.', 'success'); // Toast oznámení
            }
            
            header('Location: ' . BASE_URL . '/index.php?url=anime/detail/' . $id);
            exit;
        }

        // --- 2. NAČÍTÁNÍ DAT ---
        $anime = $animeModel->getById($id);
        if (!$anime) {
            die("Hledané anime neexistuje.");
        }

        $titles = $animeModel->getTitles($id);
        $comments = $animeModel->getComments($id);
        $gallery = $animeModel->getGallery($id);
        
        $myListEntry = null;
        if (isset($_SESSION['user_id'])) {
            $myListEntry = $userListModel->getEntry($_SESSION['user_id'], $id);
        }
        $currentUser = null;
        if (isset($_SESSION['user_id'])) {
            $userModel = $this->model('User');
            // Předpokládám, že tvůj model User má metodu getById (nebo podobnou)
            $currentUser = $userModel->getById($_SESSION['user_id']);
        }
        // --- 3. VYKRESLENÍ ŠABLONY (Tohle musí být na absolutním konci metody!) ---
        $this->view('anime/detail', [
            'anime'       => $anime,
            'titles'      => $titles,
            'comments'    => $comments,
            'gallery'     => $gallery,
            'myListEntry' => $myListEntry,
            'currentUser' => $currentUser
        ]);
    }
    
    public function edit($id = null) {
        // Kontrola admina
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if (!$id) { header('Location: ' . BASE_URL . '/index.php?url=admin/dashboard'); exit; }

        $animeModel = $this->model('Anime');
        $anime = $animeModel->getById($id);
        $titles = $animeModel->getTitles($id);
        $gallery = $animeModel->getGallery($id);
        if (!$anime) { die("Hledané anime neexistuje."); }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentImage = $_POST['current_image'] ?? null;
            $imageName = $currentImage; // Předvyplníme starým obrázkem

            // Pokud uživatel nahrál nový obrázek
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $fileMimeType = mime_content_type($_FILES['cover_image']['tmp_name']);

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                    $imageName = uniqid('cover_') . '.' . $extension;
                    $targetPath = '../public/uploads/covers/' . $imageName;
                    
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                        // Smažeme starý obrázek, pokud existoval
                        if (!empty($currentImage) && file_exists('../public/uploads/covers/' . $currentImage)) {
                            unlink('../public/uploads/covers/' . $currentImage);
                        }
                    }
                }
            }
            // Zpracování galerie
            if (!empty($_FILES['gallery']['name'][0])) {
                foreach ($_FILES['gallery']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                        $extension = pathinfo($_FILES['gallery']['name'][$key], PATHINFO_EXTENSION);
                        $fileName = 'gal_' . uniqid() . '.' . $extension;
                        move_uploaded_file($tmpName, '../public/uploads/gallery/' . $fileName);
                        
                        // OPRAVA: Používáme $id, protože to je ID upravovaného anime
                        $animeModel->addGalleryImage($id, $fileName);
                    }
                }
            }

            $data = [
                'primary_title'  => trim($_POST['primary_title'] ?? ''),
                'title_english'  => trim($_POST['title_english'] ?? ''),
                'title_japanese' => trim($_POST['title_japanese'] ?? ''),
                'title_romaji'   => trim($_POST['title_romaji'] ?? ''),
                'description'    => trim($_POST['description'] ?? ''),
                'episodes'       => !empty($_POST['episodes']) ? (int)$_POST['episodes'] : null,
                'image'          => $imageName
            ];

            if (!empty($data['primary_title'])) {
                $animeModel->update($id, $data);
                header('Location: ' . BASE_URL . '/index.php?url=admin/dashboard');
                exit;
            }
        }

        $this->view('anime/edit', [
            'anime' => $anime, 
            'titles' => $titles,
            'gallery' => $gallery
        ]);
    }
    // Smazání komentáře
    public function deleteComment($comment_id = null) {
        if (!$comment_id || !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $animeModel = $this->model('Anime');
        $comment = $animeModel->getCommentById($comment_id);

        if ($comment) {
            // BEZPEČNOSTNÍ KONTROLA: Smazat může autor NEBO admin
            if ($comment['user_id'] == $_SESSION['user_id'] || ($_SESSION['user_role'] ?? '') === 'admin') {
                $animeModel->deleteComment($comment_id);
                $this->setToast('Komentář byl smazán.', 'success');
            } else {
                $this->setToast('Nemáš oprávnění smazat tento komentář.', 'error');
            }
            // Přesměrujeme zpět na detail daného anime
            header('Location: ' . BASE_URL . '/index.php?url=anime/detail/' . $comment['anime_id']);
            exit;
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // Úprava komentáře
    public function editComment($comment_id = null) {
        if (!$comment_id || !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $animeModel = $this->model('Anime');
        $comment = $animeModel->getCommentById($comment_id);

        if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
            $this->setToast('Nemáš oprávnění upravovat tento komentář.', 'error');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Pokud uživatel odeslal upravený text
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
            $content = trim($_POST['content']);
            if (!empty($content)) {
                $animeModel->updateComment($comment_id, $content);
                $this->setToast('Komentář byl upraven.', 'success');
                header('Location: ' . BASE_URL . '/index.php?url=anime/detail/' . $comment['anime_id']);
                exit;
            }
        }

        // Zobrazíme jednoduchou šablonu pro editaci jednoho komentáře
        $this->view('anime/edit_comment', ['comment' => $comment]);
    }
}