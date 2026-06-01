<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-6xl mx-auto px-6 py-12 w-full flex-1">
    <div class="flex gap-4 mb-8 border-b border-slate-200 pb-4">
        <a href="<?= BASE_URL ?>/index.php?url=admin/dashboard" class="px-5 py-2.5 bg-sakura text-white rounded-xl font-bold shadow-md transition-all">
            🎬 Správa Anime
        </a>
        <a href="<?= BASE_URL ?>/index.php?url=user/index" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 font-bold transition-colors">
            👥 Správa Uživatelů
        </a>
    </div> 

    <div class="bg-white rounded-xl shadow-sm border border-sakura-light overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 border-b border-sakura-light text-slate-800 uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">Obrázek</th>
                    <th class="px-6 py-4 font-bold">Název</th>
                    <th class="px-6 py-4 font-bold">Epizody</th>
                    <th class="px-6 py-4 font-bold text-right">Akce</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($animes)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Žádná data v databázi.</td></tr>
                <?php else: ?>
                    <?php foreach ($animes as $anime): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-400">#<?= $anime['id'] ?></td>
                        <td class="px-6 py-4">
                            <?php if(!empty($anime['image'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($anime['image']) ?>" class="w-10 h-14 object-cover rounded shadow-sm">
                            <?php else: ?>
                                <div class="w-10 h-14 bg-slate-200 rounded flex items-center justify-center text-[10px] text-slate-400">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($anime['primary_title']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($anime['episodes'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="<?= BASE_URL ?>/index.php?url=anime/edit/<?= $anime['id'] ?>" class="text-blue-500 hover:text-blue-700 font-bold">Upravit</a>
                            <a href="<?= BASE_URL ?>/index.php?url=admin/delete/<?= $anime['id'] ?>" onclick="return confirm('Opravdu smazat?');" class="text-red-500 hover:text-red-700 font-bold">Smazat</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>