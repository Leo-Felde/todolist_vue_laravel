# Projeto Todo List com Vue.js 3, Quasar e PHP Laravel

Este projeto tem como objetivo criar uma SPA de lista de tarefas utilizando **Vue.js 3 composition API** com o framework **Quasar VITE** e **PHP Laravel**.

## Tecnologias utilizadas
<ul>
  <li>
    <a href="https://laravel.com/">Laravel</a>
  </li>
  <li>
    <a href="https://vuejs.org/guide/introduction.html">Vue.js 3</a>
  </li>
  <li>
    <a href="https://quasar.dev/">Quasar</a>
  </li>
  <li>
    <a href="https://axios-http.com/">Axios</a>
  </li>
</ul>

## Requisitos
- [Node.js](https://nodejs.org/en/download)
- [Composer](https://getcomposer.org/)
- Servidor PHP (E.g [Apache](https://httpd.apache.org/) ou [Nginx](https://nginx.org/))

## Instalação
### instalar dependências
Abra um terminal e digite os seguintes comandos:</br>
<sub>Ou utilize git-hub desktop</sub>
#### 1. Clonar repositório:
   ```bash
   git clone https://github.com/Leo-Felde/desafio_todolist_vue_laravel.git
   cd desafio_todolist_vue_laravel
   ```

#### 2. Instalar dependências
Abra um terminal no diretório do projeto e digite os seguintes comandos:</br>
**Front-end**
   ```bash
   cd frontend
npm install
   ```

**Back-end**
   ```bash
   cd ../backend
npm install
composer install
   ```
   
## Desenvolvimento
Abra um terminal no diretório do projeto e digite os seguintes comandos:</br>

**Servidor back-end**
```
composer run dev
```

**Front-end**
```
quasar dev
```
