<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Pessoas</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/app.js" defer></script>
</head>
<body>

    <main class="container">
        <h1>Cadastro de Pessoas</h1>

        <section class="card form-card">
            <form id="pessoaForm">
                <input type="hidden" id="pessoaId">

                <label>Nome
                    <input type="text" id="nome" name="nome" required>
                </label>

                <label>CPF
                    <input type="text" id="cpf" name="cpf" maxlength="14" required>
                </label>

                <label>Idade
                    <input type="number" id="idade" name="idade" min="0" required>
                </label>

                <button type="submit" id="btnSalvar" class="button">Salvar</button>
                <button type="button" id="btnCancelar" class="secundary">Cancelar</button>
            </form>
        </section>

        <section class="card list-card">
            <h2>Pessoas Cadastradas</h2>
            <ul id="listaPessoas"></ul>
        </section>
    </main>

</body>
</html>
