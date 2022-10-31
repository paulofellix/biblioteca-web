# Biblioteca web projeto uninorte

Projeto para feira de tecnologia uninorte.

## Caso de Uso

Todos nós já frequentamos bibliotecas em algum momento de nossa vida acadêmica. Em
linhas gerais, uma biblioteca possui livros que devem ser emprestados e devolvidos. Neste
estudo de caso em particular, reduziremos o escopo de uma biblioteca com a finalidade de
facilitar a compreensão do modelo de dados que apresentaremos. Entretanto, nada impede
que o leitor acrescente outras funcionalidades no modelo aqui apresentado.
Assim, um sistema de bibliotecas, para este estudo de caso, consiste das seguintes
funcionalidades:
· Cadastro de Livros: Um cadastro de livro deve armazenar informações relativas ao Título do
livro, Editora, Edição, Ano de Publicação, Autores, Assunto. Sabe-se também que um livro pode
possuir vários exemplares e neste caso, são emprestados os exemplares e não os títulos.
· Cadastro de Alunos: Um cadastro de aluno deve possuir número de matrícula, nome,
endereço, telefone, telefone celular, CPF, RG, e-mail.
· Cadastro de Professores: Um cadastro de professor deve possuir nome, endereço, telefone,
telefone celular, CPF, RG, e-mail, titulação.
· Empréstimo de Livros: Esta funcionalidade refere-se ao empréstimo de livros propriamente
dita aos alunos ou professores da instituição. Este empréstimo deve armazenar a data do
empréstimo, data prevista de devolução, exemplar emprestado.
· Devolução de Livros: Após serem emprestados, os livros podem ser devolvidos. Neste
ponto, os livros podem ter devoluções parciais, ou seja, um aluno pode pegar emprestado 3
livros diferentes e querer devolver apenas um.


## Requisitos
- PHP 8.1+
- Composer
- NodeJS
- Postgres



## Instalação
```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```
