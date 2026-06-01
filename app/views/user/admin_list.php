<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-6xl mx-auto px-6 py-12 w-full flex-1">
    
    <div class="flex gap-4 mb-8 border-b border-slate-200 pb-4">
        <a href="<?= BASE_URL ?>/index.php?url=admin/dashboard" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 font-bold transition-colors">
            🎬 Správa Anime
        </a>
        <a href="<?= BASE_URL ?>/index.php?url=user/index" class="px-5 py-2.5 bg-sakura text-white rounded-xl font-bold shadow-md transition-all">
            👥 Správa Uživatelů
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                    <th class="p-4 font-bold">ID</th>
                    <th class="p-4 font-bold">Uživatel</th>
                    <th class="p-4 font-bold">E-mail</th>
                    <th class="p-4 font-bold">Role</th>
                    <th class="p-4 font-bold text-right">Akce</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-slate-500 text-sm font-bold">#<?= $u['id'] ?></td>
                        <td class="p-4 font-bold text-slate-800 flex items-center gap-3">
                            <?php if (!empty($u['avatar'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($u['avatar']) ?>" class="w-8 h-8 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-sakura-light text-sakura flex items-center justify-center font-black text-xs">
                                    <?= strtoupper(substr($u['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <?= htmlspecialchars($u['username']) ?>
                        </td>
                        <td class="p-4 text-slate-600 text-sm"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="p-4">
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-black uppercase tracking-wider">Admin</span>
                            <?php else: ?>
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">User</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-right">
                            <?php if ($u['id'] != $_SESSION['user_id']): // Nesmí smazat sám sebe ?>
                                <a href="<?= BASE_URL ?>/index.php?url=user/delete/<?= $u['id'] ?>" 
                                   onclick="return confirm('Opravdu nenávratně smazat uživatele <?= htmlspecialchars($u['username']) ?>?');" 
                                   class="text-red-500 hover:underline text-sm font-bold">
                                   Smazat
                                </a>
                            <?php else: ?>
                                <span class="text-slate-300 text-sm font-bold cursor-not-allowed">Smazat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<?php require_once '../app/views/layout/footer.php'; ?>