### Instalação

* Via git:
    - De preferência, acesse a raiz do seu projeto;
    - Faça o download:
    ```
        $ git submodule add https://github.com/GainTime/heart.git
    ```
    - Pronto :smile:

### Estrutura
```.
├── controller
│   └── base.php
├── model
│   └── base.php
├── helpers
│   ├── print.php
│   └── session.php
├── sos
│   ├── drugstore.php
│   ├── error.php
│   └── ops.php
├── pulse.php
├── .vBlood
├── rAtrium.php
├── lAtrium.php
└── README.md
```

## Uso
[WIKI com mais detalhes](https://github.com/GainTime/heart/wiki)

Em cada view (página) do seu projeto inclua uma chamada para  `heart/pulse`.
```php
<?php require_once('heart/pulse.php'); ?>
```
Isso irá carregar todos os arquivos necessários para trabalhar naquela view, caso atenda ao padrão exigido pelo heart, que é o seguinte:
- **Views**:
  - **create**: new-entity.php
  - **edit**: edit-entity.php
  - **list**: entitys.php
- **Controller**:
  - **File:** entity_controller.php
  - **Class:** Entity_controller
- **Model**:
  - **File**: entity.php
  - **Class:**Entity
- **DB Table**: entitys

Caso uma view queira tratar de mais de uma entidade, o heart saberá o que fazer caso se utilize:
```php
<?php $contexts = ['entity2', 'entity3', 'entity4']; ?>
<?php require_once('../heart/pulse.php'); ?>
```

O que o `heart/pulse` faz é fazer o `require_once` de todas as controllers solicitadas, sendo que caso o nome do arquivo esteja dentro do padrão, ele vai buscar a controller que tem relação com o nome do arquivo em que foi chamado. Além disso, ele faz o `require_once` de todas as helpers do `heart/helpers`, o que te permite, dentre outras coisas, definir permissões de acesso para as páginas.
### Models
Nas models, siga o padrão:
```php
<?php
    require_once('../heart/model/base.php');

    class User extends \Model\Base {
        public $fillable = ['picture', 'name', 'email', 'level'];
    }

```
### Controllers
Nas controllers, siga o padrão:
```php
<?php
    require_once('../heart/controller/base.php');
    require_once('../models/user.php');

    class User_controller extends \Controller\Base {

        public $fillneeded = ['name' => 'nome', 'email' => 'E-mail'];
        public $location = '../views/users';
    }

    $obj = new User_controller();
    $users = $obj->loadAll();
    $user = $obj->one();
```
