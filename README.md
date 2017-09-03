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
│   ├── session.php
│   └── file.php
├── sos
│   ├── drugstore.php
│   ├── error.php
│   └── ops.php
├── pulse.php
├── .vBlood
├── .cBlood ( Generate )
├── heart.php
├── remedyBlood.php
├── rAtrium.php
├── lAtrium.php
└── README.md
```

### Uso
[WIKI com mais detalhes](https://github.com/GainTime/heart/wiki)

Em cada View e Controller do seu projeto inclua uma chamada para  `heart/heart`.
```php
<?php require_once('heart/heart.php'); ?>
```

Esse é o Gatilho do Heart, o arquivo `heart/heart` funciona como inicializador e irá carregar todo o Heart para trabalhar junto com o seu projeto.

#### Padrões

O Heart exige o seguinte padrão na estrutura do seu projeto:

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
<?php require_once('../heart/heart.php'); ?>
```

O que o `heart/pulse` faz é fazer o `require_once` de todas as controllers solicitadas, sendo que caso o nome do arquivo esteja dentro do padrão, ele vai buscar a controller que tem relação com o nome do arquivo em que foi chamado. Além disso, ele faz o `require_once` de todas as helpers do `heart/helpers`, o que te permite, dentre outras coisas, definir permissões de acesso para as páginas.

#### Models

Nas models, siga o padrão:
```php
<?php

    class User extends \Model\Base {
        public $fillable = ['picture', 'name', 'email', 'level', 'jobs_id'];
        public $relationship = ['job'];
    }

```
#### Controllers

Nas controllers, siga o padrão:
```php
<?php
    require_once('../heart/heart.php');
    require_once('../models/user.php'); //Caso o AutoLoad esteja habilitado, essa linha não é necessária.

    class User_controller extends \Controller\Base {

        public $fillneeded = ['name' => 'nome', 'email' => 'E-mail'];
        public $location = '../views/users';
    }

    $obj = new User_controller();
    $users = $obj->loadAll();
    $user = $obj->one();
```

### Recursos

#### AutoLoad de Classes e seus Recursos

- O Heart basicamente precisa conhecer o seu projeto para fazer um auto-carregamento quando necessário. Logo, toda vez que ele identificar que um objeto foi instanciado, ele guardará o local do arquivo que é a razão da sua existência, em outras palavras, a implementação da sua Classe. O caminho que ele escolhe para fazer esse tipo de implementação exige que ele tenha permissão nível 6, pelo menos dentro do próprio escopo de trabalho ( Project/heart/. ), ou não funcionará.

- O AutoLoad  tem como requisito o nível 6 de acesso ao Heart. Ele precisa Criar, Editar e Ler arquivos no seu Escopo. Porém nas configurações (.vBlood), essas funcionalidades podem ser desativadas diminuindo o nível para 4 ( Default ).

`autoLoad = "false"`

- Todas as Classes são salvas no (.cBlood). Caso o caminho da Classe esteja incorreta, o Heart irá reparar imediatamente, assim que o erro for detectado. Estrutura de Configuração:

`User : '/models/user.php'`

- Se o AutoLoad estiver ativado e na primeira inicialização do Heart ele não conhecer nada da estrutura do seu projeto, ele irá fazer uma verificação geral para tentar adivinhar o arquivo onde cada classe estará definida.

- Cuidado! Se você modificar o nome do arquivo e o nome da classe, ou adicionar novos arquivos com novas definições de classes ao seu projeto depois do primeiro reconhecimento, não espere que o Heart o detecte. Essa situação é bastante comum durante o desenvolvimento do projeto, logo no arquivo de configuração tem a opção do Heart fazer o reconhecimento toda vez que ele for chamado. Essa opção só deve ser utilizada somente durante o desenvolvimento, quando o projeto realmente estiver finalizado, desative-o.

`forceLoad = "false"`

- O Heart também tem a capacidade de memorizar as Controllers do seu Projeto, dando liberdade ao programador de chamar livremente Controllers externas do Escopo.

- Os recursos a seguir só estarão disponíveis caso o AutoLoad esteja habilitado.

##### Acesso entre Models

- Para que a funcionalidade de agregação de classes funcione corretamente, no Banco de Dados é preciso que siga um padrão na nomeação de chaves estrangeiras:

> jobs_id

- No exemplo acima, apresenta um campo da tabela de 'users' que é uma chave estrangeira para a tabela 'jobs' ( Lembre-se, o nome das tabelas devem obedecer o padrão do Heart! ). O padrão deve ser seguido em todas as chaves estrangeiras de um projeto:

> tables_id

- 'tables' representa o nome da Table seguido de 's' como o padrão do Heart + '_id'. É importante lembrar que o usuário tem a opção de não seguir o padrão estabelecido, entretanto restringe o uso da agregação de classes e qualquer tentativa de utilizar o recurso obterá algum tipo de erro de retorno **( Heart não tem dependência de funcionamento com Agregação de Classes! )**.

- Seguindo o exemplo acima, digamos que temos um código com a seguinte estrutura:

```php
$user = new User ("name" => "Pedro", "jobs_id" => "2");
```

- O Heart te apresentará um novo caminho para acessar os atributos da Classe Job. Basicamente, um objeto Job será criado com os atributos da classe. Nesse processo o banco de dados é acessado para obter os atributos, logo, verifique a Conexão do Heart para o uso de tal artifício.

```php
var_dump($user->job->name);die; //Retornará o Nome do Trabalho do Objeto. Lembre-se que name é um atributo da classe Job no nosso exemplo!
```

- Porém, há um detalhe! Na implementação da Classe User precisamos informar ao Heart que há um relacionamento, para que ele saiba que você quer utilizar a agregação de classes. Atenção! Caso você não informe ao Heart a relação entre Classes ele não permitirá que você utilize o recurso acima.

```php
class User extends \Model\Base {    
     public $fillable = ['picture', 'name', 'email', 'level', 'jobs_id'];
     public $relationship = ['job'];
}
```

- O exemplo acima é auto-explicativo, a array ``$relationship`` é identificado pelo Heart e a string ``'job'`` segue o padrão: {Nome da Chave Estrangeira} - 's_id'. **Consequentemente, o Nome da Classe Lowercase!**



##### Acesso entre Controllers

- Esse é o tópico mais interessante e importante sobre o AutoLoad do Heart. Quando nos referimos as Controllers é preciso muita atenção. Algumas vezes o programador precisará exibir informações de diversos objetos diferentes na mesma View, o que acarreta o uso de várias Controllers e com o AutoLoad teremos dois caminhos, que dependendo da situação, a escolha entre os dois deve ser analisada.

###### Controllers e Views ( Funciona normalmente sem o AutoLoad )

- Caso você deseje somente exibir informações de outros objetos na mesma View o Heart oferece a opção de declarar um Array `$contexts` antes de chama-lo. Depois que chamado, o Heart cuidará de chamar todas as Controllers necessárias para que a View funcione.

```php
<?php $contexts = ['user', 'job']; ?>
<?php require_once('../heart/heart.php'); ?>
```

###### Controllers e Controllers

- Porém, teremos situações onde na mesma página, Controllers diferentes e especificas terão que executar ações particulares, ou seja, as Controllers irão interagir entre si. Nesse caso o uso do AutoLoad é recomendado mas deve ser usado com Cautela. Digamos que temos a seguinte View:

> 1. new-user.php

- O contexto desse View será a Criação de um User, entretanto iremos criar um Job caso o novo usuário requisite um Job que ainda não exista no Banco de Dados. Há meios tradicionais para resolver essa situação mas o AutoLoad é mais uma alternativa.

- Analisemos o caso, a Controller User terá a Permissão de Executar a Ação da Página de Criar e ela que **decidirá** se o Job será criado ou não! Entendeu? A Controller Job não tem permissão para Executar a Ação da Página então nem pense duas vezes e adicione:

`<?php $contexts = ['job']; ?>`

- Com isso nós já podemos utilizar o conteúdo que a Controller Job nos oferece, a lista de todos os Jobs. A próxima etapa iremos para a:

> user_controller.php

- Aqui o programador terá que pensar por si em cada situação, no nosso caso teríamos que criar uma método de criação de usuário, E no meio da lógica teríamos que agregar a Controller de Job da seguinte maneira:

`$jobController = new Job_controller(false);`

- O que nós estamos fazendo é criando uma ligação através do AutoLoad com a Controller de Job, mas porque o `false`? **Simplesmente, estamos dizendo que a Controller será criada mas não executará automaticamente a Ação da Página. ( Por padrão todas as Controllers quando criadas, tentam executar automaticamente a ação da página ).**

- Aqui fica fácil, o Heart oferece diversos métodos Default para o uso. Na nossa situação a Controller User está no Controle da situação. Então é recomendado utilizar `$this->store()`, porém o metódo `store()` redireciona para a página configurada na Controller que a chamou. Logo, devemos utilizar para o Job o método `$jobController->storeExt()`. Um exemplo seria:

```
public function store() {

//Linhas de Código que Define a Lógica.
$jobController = new Job_controller(false);
//Linhas de Código que Define a Lógica
$_REQUEST['jobs_id'] = $jobController->storeExt();
//LInhas de Código que Define a Lógica
parent::store();
}
```

- Esse é um caminho alternativo que utiliza o AutoLoad do Heart, lembre-se de ter cautela.



#### Método Prints::itPersonal

O User tem Level que segue as seguintes regras:

100 - Administrador
50 - Comum

Caso o Level de User tenha que ser exibido pelo Heart usaríamos:

`Prints::it($user, "level");
`
Porém, o incoveniente desse método é a falta de tratamento que o `Prints::it` oferece. O resultado será a exibição na tela das Strings "50" ou "100".

O método `Prints::itPersonal` contorna este incoveniente, oferecendo ao programador a liberdade de tratar a saída:

`Prints::itPersonal($user, "level", ["50" => "Comum", "100" => "Administrador"]);
`

#### Gatilho do Heart

Agora o Heart tem um gatilho único para o projeto, e livra do desenvolvedor o trabalho de inicializar o Heart de forma correta e personalizada. Todos os arquivos do projeto ( Views e Controllers ), deve inicializar com:

`require_once( __DIR__ . '/heart/heart.php');
`
