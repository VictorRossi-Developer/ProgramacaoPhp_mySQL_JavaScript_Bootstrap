<?php

require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$contato = null;

if ($id) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM contatos
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $contato = $stmt->fetch();

    if (!$contato) {
        header('Location: index.php');
        exit;
    }
}

$mensagem = $_GET['msg'] ?? '';

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= $contato ? 'Editar contato' : 'Novo contato' ?>
        - Minha Agenda
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            background-color: #f5f6f8;
        }

        .form-card {
            max-width: 700px;
            margin: 40px auto;
            border: 0;
            border-radius: 16px;
        }

        .foto-preview {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
        }

        .foto-inicial {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background-color: #212529;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 40px;
            font-weight: 600;
        }

    </style>

</head>

<body>

<div class="container py-4">

    <div class="card form-card shadow-sm">

        <div class="card-body p-4">

            <!-- CABEÇALHO -->

            <div class="d-flex align-items-center mb-4">

                <a
                    href="index.php"
                    class="btn btn-light me-3"
                    title="Voltar"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>

                    <h3 class="mb-1">

                        <?= $contato
                            ? 'Editar contato'
                            : 'Novo contato'
                        ?>

                    </h3>

                    <p class="text-muted mb-0">

                        Preencha as informações do contato.

                    </p>

                </div>

            </div>


            <!-- MENSAGEM DE ERRO -->

            <?php if ($mensagem): ?>

                <div
                    id="mensagem"
                    class="alert alert-danger border-0"
                >

                    <i class="bi bi-exclamation-circle me-1"></i>

                    <?= htmlspecialchars($mensagem) ?>

                </div>

            <?php endif; ?>


            <!-- FORMULÁRIO -->

            <form
                action="salvar.php"
                method="POST"
                enctype="multipart/form-data"
                id="formContato"
                novalidate
            >

                <?php if ($contato): ?>

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $contato['id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="foto_atual"
                        value="<?= htmlspecialchars(
                            $contato['foto'] ?? ''
                        ) ?>"
                    >

                <?php endif; ?>


                <!-- FOTO -->

                <div class="text-center mb-4">

                    <?php if (!empty($contato['foto'])): ?>

                        <img
                            src="uploads/<?= htmlspecialchars(
                                $contato['foto']
                            ) ?>"
                            class="foto-preview mb-3"
                            id="previewFoto"
                            alt="Foto do contato"
                        >

                        <div
                            class="foto-inicial mx-auto mb-3 d-none"
                            id="previewInicial"
                        >
                            <i class="bi bi-person"></i>
                        </div>

                    <?php else: ?>

                        <div
                            class="foto-inicial mx-auto mb-3"
                            id="previewInicial"
                        >
                            <i class="bi bi-person"></i>
                        </div>

                        <img
                            src=""
                            class="foto-preview mb-3 d-none"
                            id="previewFoto"
                            alt="Pré-visualização"
                        >

                    <?php endif; ?>


                    <div>

                        <label
                            for="foto"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-camera me-1"></i>

                            Escolher foto

                        </label>

                        <input
                            type="file"
                            class="d-none"
                            id="foto"
                            name="foto"
                            accept="image/jpeg,image/png,image/webp"
                        >

                        <div class="form-text mt-2">

                            JPG, PNG ou WEBP.
                            Máximo de 2 MB.

                        </div>

                    </div>

                </div>


                <!-- NOME -->

                <div class="mb-3">

                    <label
                        for="nome"
                        class="form-label"
                    >
                        Nome *
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        value="<?= htmlspecialchars(
                            $contato['nome'] ?? ''
                        ) ?>"
                        placeholder="Digite o nome completo"
                        required
                        autocomplete="name"
                    >

                    <div class="invalid-feedback">

                        Digite um nome válido.

                    </div>

                </div>


                <!-- TELEFONE -->

                <div class="mb-3">

                    <label
                        for="telefone"
                        class="form-label"
                    >
                        Telefone *
                    </label>

                    <input
                        type="tel"
                        class="form-control"
                        id="telefone"
                        name="telefone"
                        value="<?= htmlspecialchars(
                            $contato['telefone'] ?? ''
                        ) ?>"
                        placeholder="(11) 98765-4321"
                        maxlength="15"
                        inputmode="numeric"
                        required
                        autocomplete="tel"
                    >

                    <div class="invalid-feedback">

                        Digite um telefone válido com DDD.

                    </div>

                </div>


                <!-- E-MAIL -->

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        E-mail *
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars(
                            $contato['email'] ?? ''
                        ) ?>"
                        placeholder="exemplo@email.com"
                        required
                        autocomplete="email"
                    >

                    <div class="invalid-feedback">

                        Digite um e-mail válido.

                    </div>

                </div>


                <!-- ENDEREÇO -->

                <div class="mb-4">

                    <label
                        for="endereco"
                        class="form-label"
                    >
                        Endereço *
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="endereco"
                        name="endereco"
                        value="<?= htmlspecialchars(
                            $contato['endereco'] ?? ''
                        ) ?>"
                        placeholder="Rua, número, bairro e cidade"
                        required
                        autocomplete="street-address"
                    >

                    <div class="invalid-feedback">

                        Digite o endereço.

                    </div>

                </div>


                <!-- BOTÕES -->

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="index.php"
                        class="btn btn-light"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        <?= $contato
                            ? 'Salvar alterações'
                            : 'Adicionar contato'
                        ?>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const form =
        document.getElementById('formContato');

    const nome =
        document.getElementById('nome');

    const telefone =
        document.getElementById('telefone');

    const endereco =
        document.getElementById('endereco');

    const inputFoto =
        document.getElementById('foto');

    const previewFoto =
        document.getElementById('previewFoto');

    const previewInicial =
        document.getElementById('previewInicial');


    /*
     * NOME
     *
     * Permite apenas letras e espaços.
     */

    nome.addEventListener('input', function () {

        this.value = this.value.replace(
            /[^A-Za-zÀ-ÿ\s]/g,
            ''
        );

    });


    /*
     * TELEFONE
     *
     * Aceita somente números.
     * A formatação é feita automaticamente.
     */

    telefone.addEventListener('input', function () {

        let numeros =
            this.value.replace(/\D/g, '');

        numeros =
            numeros.substring(0, 11);


        if (numeros.length <= 2) {

            this.value = numeros;

        }

        else if (numeros.length <= 6) {

            this.value =
                '(' +
                numeros.substring(0, 2) +
                ') ' +
                numeros.substring(2);

        }

        else if (numeros.length <= 10) {

            this.value =
                '(' +
                numeros.substring(0, 2) +
                ') ' +
                numeros.substring(2, 6) +
                '-' +
                numeros.substring(6);

        }

        else {

            this.value =
                '(' +
                numeros.substring(0, 2) +
                ') ' +
                numeros.substring(2, 7) +
                '-' +
                numeros.substring(7, 11);

        }

    });


    /*
     * FOTO
     */

    inputFoto.addEventListener(
        'change',
        function () {

            const arquivo =
                this.files[0];

            if (!arquivo) {
                return;
            }


            /*
             * TAMANHO
             */

            if (arquivo.size > 2 * 1024 * 1024) {

                alert(
                    'A imagem deve ter no máximo 2 MB.'
                );

                this.value = '';

                return;
            }


            /*
             * FORMATO
             */

            const tiposPermitidos = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (
                !tiposPermitidos.includes(
                    arquivo.type
                )
            ) {

                alert(
                    'Escolha uma imagem JPG, PNG ou WEBP.'
                );

                this.value = '';

                return;
            }


            /*
             * PRÉ-VISUALIZAÇÃO
             */

            const leitor =
                new FileReader();

            leitor.onload =
                function (evento) {

                    previewFoto.src =
                        evento.target.result;

                    previewFoto.classList.remove(
                        'd-none'
                    );

                    if (previewInicial) {

                        previewInicial.classList.add(
                            'd-none'
                        );

                    }

                };

            leitor.readAsDataURL(arquivo);

        }
    );


    /*
     * VALIDAÇÃO DO FORMULÁRIO
     */

    form.addEventListener(
        'submit',
        function (event) {

            /*
             * Remove qualquer formatação
             * do telefone apenas para verificar
             * a quantidade de números.
             */

            const numeros =
                telefone.value.replace(
                    /\D/g,
                    ''
                );


            /*
             * Telefone precisa ter:
             *
             * 10 números para telefone fixo
             * ou
             * 11 números para celular.
             */

            if (
                numeros.length !== 10 &&
                numeros.length !== 11
            ) {

                telefone.setCustomValidity(
                    'Digite um telefone válido com DDD.'
                );

            } else {

                telefone.setCustomValidity('');

            }


            /*
             * O navegador verifica automaticamente:
             *
             * nome obrigatório
             * telefone obrigatório
             * e-mail obrigatório
             * e-mail válido
             * endereço obrigatório
             */

            if (!form.checkValidity()) {

                event.preventDefault();

                event.stopPropagation();

                form.classList.add(
                    'was-validated'
                );

            }

        }
    );


    /*
     * MENSAGEM DE ERRO
     */

    const mensagem =
        document.getElementById('mensagem');

    if (mensagem) {

        setTimeout(
            function () {

                mensagem.style.transition =
                    'opacity 0.5s';

                mensagem.style.opacity = '0';


                setTimeout(
                    function () {

                        mensagem.remove();

                    },
                    500
                );

            },
            3000
        );

    }

});

</script>

</body>

</html>