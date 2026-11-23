<img width="1254" height="1254" alt="mymishi" src="https://github.com/user-attachments/assets/49fa1f3c-0f69-4275-a1d7-50ed0d2c82fb" />
# 🩷 MyMishi — Sistema de Gestão de Catálogo, Inventário e Precificação

Aplicação web desenvolvida em **Laravel 13** e **Bootstrap 5** para a gestão completa de uma loja de artigos artesanais (**mymishi**), integrando catálogo público, controlo de stock, gestão de matérias-primas, cálculo dinâmico de preços de venda e controlo de acessos por perfis de utilizador.

---

## ✨ Funcionalidades Principais

### 🛍️ Catálogo Público & Gestão de Artigos
* **Listagem de Categorias e Produtos:** Visualização de categorias com contagem dinâmica de artigos associados e navegação fluida para os respetivos produtos.
* **Gestão de Imagens:** Suporte para seleção de ícones predefinidos ou upload personalizado (validado até 2 MB), com limpeza automática de ficheiros antigos e modal *Lightbox* para visualização em tamanho real.
* **Controlo de Stock:** Indicação visual de disponibilidade em tempo real (`Em stock`, `Stock baixo` e `Esgotado`).

### 📊 Dashboard & Alertas em Tempo Real
* **Indicadores (KPIs):** Contagem total de categorias, modelos criados, unidades disponíveis em loja e utilizadores registados.
* **Alertas de Stock Baixo:** Monitorização automática de produtos com quantidade reduzida (`≤ 2 un.`) e matérias-primas abaixo do nível mínimo, com acesso direto para reposição.

### 🧶 Inventário de Materiais & Precificação (Exclusivo Admin)
* **Gestão de Matérias-Primas:** Controlo de materiais por unidade (`un.`, `m`, etc.), custo unitário e exportação de dados.
* **Calculadora Dinâmica de Preços:** Associação muitos-para-muitos (`material_product`) entre artigos e materiais gastos, cálculo automático de mão de obra por quartos de hora (`0.25h`), custos de embalagem, margem de lucro percentual e preço final de venda em tempo real.

### 🔐 Autenticação, Segurança & Perfis
Sistema de autenticação baseado em **Laravel Fortify** com três níveis de permissão:
* **Visitante (Guest):** Consulta do catálogo público e registo de nova conta.
* **Utilizador (`user`):** Consulta do catálogo, visualização de utilizadores e edição do próprio perfil (incluindo seleção de avatar).
* **Moderador (`mod`):** Acesso ao Dashboard, gestão de categorias, produtos e inventário de materiais.
* **Administrador (`admin`):** Acesso total, incluindo eliminação de registos, gestão de perfis/dados fiscais (NIF e morada) e módulo de precificação.
* **Segurança Adicional:**
  * Recuperação de password por email com token seguro.
  * Logout automático após **5 minutos de inatividade**, com modal de aviso e contagem decrescente nos últimos 30 segundos.

---

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.3+, Laravel 13, Laravel Fortify
* **Frontend:** Blade Templates, Bootstrap 5.3, JavaScript (ES6+)
* **Base de Dados:** MySQL / MariaDB (Eloquent ORM, Migrations, Seeders & Factories)

---

## 🚀 Instalação e Execução Local

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com/LynnLecne/MyMishi/
   cd mymishi
   ```

2. **Instalar dependências PHP:**
   ```bash
   composer install
   ```

3. **Configurar o ambiente:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Edita o ficheiro `.env` com as credenciais da tua base de dados MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4. **Executar migrações e povoar a base de dados (Seeders):**
   ```bash
   php artisan migrate --seed
   ```

5. **Criar ligação simbólica para o armazenamento de imagens:**
   ```bash
   php artisan storage:link
   ```

6. **Iniciar o servidor local:**
   ```bash
   php artisan serve
   ```
   Acede no browser a `http://127.0.0.1:8000`.

---

## 📁 Estrutura de Permissões (Resumo)

| Funcionalidade | Visitante | User | Mod | Admin |
| :--- | :---: | :---: | :---: | :---: |
| Ver catálogo e produtos | ✅ | ✅ | ✅ | ✅ |
| Registar conta / Recuperar password | ✅ | — | — | — |
| Editar o próprio perfil | — | ✅ | ✅ | ✅ |
| Aceder ao Dashboard | — | — | ✅ | ✅ |
| Criar e editar Categorias, Produtos e Materiais | — | — | ✅ | ✅ |
| Apagar registos | — | — | — | ✅ |
| Módulo de Precificação | — | — | — | ✅ |

