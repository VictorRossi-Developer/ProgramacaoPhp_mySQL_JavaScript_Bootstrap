<?php
require 'config.php';

$busca = trim($_GET['busca'] ?? '');

if ($busca !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM contatos
        WHERE nome LIKE :busca
        OR telefone LIKE :busca
        OR email LIKE :busca
        OR endereco LIKE :busca
        ORDER BY nome ASC
    ");

    $stmt->execute([
        'busca' => "%$busca%"
    ]);
} else {
    $stmt = $pdo->query("
        SELECT * FROM contatos
        ORDER BY nome ASC
    ");
}

$contatos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contatos</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f8f9fa;
            color: #212529;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e5e5;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 18px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .logo-title {
            font-weight: 600;
            font-size: 16px;
        }

        .logo-subtitle {
            color: #888;
            font-size: 12px;
        }

        .menu-title {
            color: #999;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin: 0 0 10px 10px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            color: #555;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .menu-link:hover {
            background: #f1f3f5;
            color: #222;
        }

        .menu-link.active {
            background: #eaf1ff;
            color: #2563eb;
            font-weight: 600;
        }

        .main {
            margin-left: 240px;
            padding: 40px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .page-description {
            color: #777;
            font-size: 14px;
        }

        .search-box {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .contact-list {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 17px 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .contact-row:last-child {
            border-bottom: none;
        }

        .contact-row:hover {
            background: #fafafa;
        }

        .avatar {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: #eaf1ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .contact-details {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            color: #777;
            font-size: 12px;
        }

        .contact-details span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .contact-actions {
            display: flex;
            gap: 7px;
        }

        .empty {
            padding: 70px 20px;
            text-align: center;
            color: #777;
        }

        .empty-icon {
            font-size: 38px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
                padding: 15px;
                border-right: none;
                border-bottom: 1px solid #e5e5e5;
            }

            .logo {
                margin-bottom: 15px;
            }

            .menu-title {
                display: none;
            }

            .menu {
                display: flex;
                gap: 5px;
            }

            .menu-link {
                flex: 1;
                justify-content: center;
                margin: 0;
            }

            .main {
                margin-left: 0;
                padding: 25px 15px;
            }

            .contact-row {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .contact-info {
                width: calc(100% - 75px);
            }

            .contact-details {
                flex-direction: column;
                gap: 5px;
            }

            .contact-actions {
                width: 100%;
                margin-left: 70px;
            }

            .contact-actions a {
                flex: 1;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="logo">

            <div class="logo-icon">
                <i class="bi bi-telephone-fill"></i>
            </div>

            <div>
                <div class="logo-title">
                    Minha Agenda
                </div>

                <div class="logo-subtitle">
                    Contatos
                </div>
            </div>

        </div>

        <div class="menu-title">
            Menu
        </div>

        <nav class="menu">

            <a
                href="index.php"
                class="menu-link active">
                <i class="bi bi-people"></i>
                Contatos
            </a>

            <a
                href="form.php"
                class="menu-link">
                <i class="bi bi-person-plus"></i>
                Novo contato
            </a>

        </nav>

    </aside>

    <main class="main">

        <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">

            <div>

                <h1 class="page-title">
                    Contatos
                </h1>

                <div class="page-description">
                    Consulte e organize seus contatos.
                </div>

            </div>

            <a
                href="form.php"
                class="btn btn-primary px-4">
                <i class="bi bi-person-plus me-1"></i>
                Novo contato
            </a>

        </div>

        <div class="row mb-4">

            <div class="col-lg-8">

                <form method="get">

                    <div class="input-group search-box">

                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="busca"
                            class="form-control border-0 shadow-none"
                            placeholder="Pesquisar por nome, telefone ou e-mail..."
                            value="<?= htmlspecialchars($busca) ?>">

                        <?php if ($busca !== ''): ?>

                            <a
                                href="index.php"
                                class="btn bg-white border-0 text-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>

                        <?php endif; ?>

                    </div>

                </form>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <span class="text-secondary small">
                    <?= count($contatos) ?>
                    contato<?= count($contatos) == 1 ? '' : 's' ?>
                </span>

            </div>

        </div>


        <?php if (!$contatos): ?>

            <div class="contact-list">

                <div class="empty">

                    <div class="empty-icon">
                        <i class="bi bi-person-x"></i>
                    </div>

                    <h5>
                        Nenhum contato encontrado
                    </h5>

                    <p class="mb-4">
                        Adicione um novo contato para começar.
                    </p>

                    <a
                        href="form.php"
                        class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        Adicionar contato
                    </a>

                </div>

            </div>

        <?php else: ?>

            <div class="contact-list">

                <?php foreach ($contatos as $contato): ?>

                    <?php

                    $nome = $contato['nome'];

                    $nomeLimpo = preg_replace(
                        '/[^A-Za-zÀ-ÿ]/u',
                        '',
                        $nome
                    );

                    $iniciais = strtoupper(
                        substr($nomeLimpo, 0, 2)
                    );

                    if ($iniciais === '') {
                        $iniciais = '??';
                    }

                    $temFoto =
                        !empty($contato['foto']) &&
                        file_exists(
                            __DIR__ . '/uploads/' . $contato['foto']
                        );

                    ?>

                    <div class="contact-row">

                        <?php if ($temFoto): ?>

                            <img
                                src="uploads/<?= htmlspecialchars($contato['foto']) ?>"
                                class="avatar"
                                alt="Foto de <?= htmlspecialchars($nome) ?>">

                        <?php else: ?>

                            <div class="avatar-placeholder">
                                <?= htmlspecialchars($iniciais) ?>
                            </div>

                        <?php endif; ?>

                        <div class="contact-info">

                            <div class="contact-name">
                                <?= htmlspecialchars($nome) ?>
                            </div>

                            <div class="contact-details">

                                <span>
                                    <i class="bi bi-telephone"></i>
                                    <?= htmlspecialchars($contato['telefone']) ?>
                                </span>

                                <?php if (!empty($contato['email'])): ?>

                                    <span>
                                        <i class="bi bi-envelope"></i>
                                        <?= htmlspecialchars($contato['email']) ?>
                                    </span>

                                <?php endif; ?>

                                <?php if (!empty($contato['endereco'])): ?>

                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        <?= htmlspecialchars($contato['endereco']) ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="contact-actions">

                            <a
                                href="form.php?id=<?= $contato['id'] ?>"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </a>

                            <a
                                href="excluir.php?id=<?= $contato['id'] ?>"
                                class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i>
                                Excluir
                            </a>
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            
        <?php endif; ?>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const campoBusca = document.querySelector('input[name="busca"]');
            const listaContatos = document.querySelector('.contact-list');
            const linhas = document.querySelectorAll('.contact-row');

            if (campoBusca && linhas.length > 0) {

                campoBusca.addEventListener('input', function() {

                    const texto = this.value.toLowerCase().trim();
                    let encontrados = 0;

                    linhas.forEach(function(linha) {

                        const conteudo = linha.textContent.toLowerCase();

                        if (conteudo.includes(texto)) {
                            linha.style.display = 'flex';
                            encontrados++;
                        } else {
                            linha.style.display = 'none';
                        }

                    });

                });

            }

            const botoesExcluir = document.querySelectorAll(
                'a[href*="excluir.php"]'
            );

            botoesExcluir.forEach(function(botao) {

                botao.addEventListener('click', function(event) {

                    const confirmar = confirm(
                        'Deseja realmente excluir este contato?'
                    );

                    if (!confirmar) {
                        event.preventDefault();
                    }

                });

            });

        });
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


        <script>
document.addEventListener('DOMContentLoaded', function () {

    const mensagem = document.getElementById('mensagem');

    if (mensagem) {
        setTimeout(function () {
            mensagem.style.transition = 'opacity 0.5s';
            mensagem.style.opacity = '0';

            setTimeout(function () {
                mensagem.remove();
            }, 500);

        }, 3000);
    }

});
</script>

</body>

</html>