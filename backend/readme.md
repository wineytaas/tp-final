#Propriedades do sistema


##Usuario


1.  `GET /usuarios/:id`
    1. parametro: **id** do usuario desejado
    2. body: NULL
    3. return: O **id** , **nome** , **email** , **login** e **senha** do usuario selecionado

2.  `GET /usuarios`
    1. parametro: NULL
    2. body: NULL
    3. return: Os **id** , **nome** , **email** , **login** e **senha** de todos os usuarios

3.  `POST /usuarios`
    1. parametro: NULL
    2. body: { "nome": "NOME_USUARIO", "email": "EMAIL_USUARIO", "login":"LOGIN_USUARIO", "senha":"SENHA_USUARIO" }
    3. return: Dados do novo usuario **id** , **nome** , **email** , **login** e **senha**

4.  `PUT /usuarios/:id`
    1. parametro: **id** do usuario desejado
    2. body: { "nome": "NOME_USUARIO", "email": "EMAIL_USUARIO", "login":"LOGIN_USUARIO", "senha":"SENHA_USUARIO" }
    3. return: Dados atualizados do usuario **id** , **nome** , **email** , **login** e **senha**

5.  `DELETE /usuarios/:id`
    1. parametro: **id** do usuario desejado
    2. body: NULL
    3. return: **Usuario excluída**, caso true. **Erro ao excluir usuario**, case false


##Categoria


1.  `GET /categorias/:id`
    1. parametro: **id** do categoria desejada
    2. body: NULL
    3. return: O **id** , **nome** , **usuario_id**, **usuario** (**id** , **nome**, **email**, **login**, **senha**) da categoria selecionada

2.  `GET /categorias`
    1. parametro: NULL
    2. body: NULL
    3. return: Os **id** , **nome** , **usuario_id**, **usuario** (**id** , **nome**, **email**, **login**, **senha**) de todas as categorias

3.  `POST /categorias`
    1. parametro: NULL
    2. body: { "nome": "NOME_CATEGORIA", "usuario_id": "USUARIO_ID_CATEGORIA" }
    3. return: Dados da nova categoria **id** , **nome** , **usuario_id**, **usuario** (**id** , **nome**, **email**, **login**, **senha**)

4.  `PUT /categorias/:id`
    1. parametro: **id** do categoria desejada
    2. body: { "nome": "NOME_CATEGORIA", "usuario_id": "USUARIO_ID_CATEGORIA" }
    3. return: Dados atualizados da categoria **id** , **nome** , **usuario_id**, **usuario** (**id** , **nome**, **email**, **login**, **senha**)

5.  `DELETE /categorias/:id`
    1. parametro: **id** da categoria que desejado excluir
    2. body: NULL
    3. return: **Categoria excluída**, caso true. **Erro ao excluir categoria**, case false

##Tarefa


1.  `GET /tarefas/:id`
    1. parametro: **id** do tarefa desejada
    2. body: NULL
    3. return: O **id** , **nome** , **usuario_id**, **categoria_id** , **usuario** (**id** , **nome**, **email**, **login**, **senha**), categoria( **id** , **nome** , **usuario_id** )  da tarefa selecionada

2.  `GET /tarefas`
    1. parametro: NULL
    2. body: NULL
    3. return: Os **id** , **nome** , **usuario_id**, **categoria_id** , **usuario** (**id** , **nome**, **email**, **login**, **senha**), categoria( **id** , **nome** , **usuario_id** ) de todas as tarefas

3.  `POST /tarefas`
    1. parametro: NULL
    2. body: { "descricao": "DESCRICAO_TAREFA", "usuario_id": "USUARIO_ID_TAREFA" , "categoria_id": "CATEGORIA_ID_TAREFA" }
    3. return: Dados da nova tarefa **id** , **nome** , **usuario_id**, **categoria_id** , **usuario** (**id** , **nome**, **email**, **login**, **senha**), categoria( **id** , **nome** , **usuario_id** )

4.  `PUT /tarefas/:id`
    1. parametro: **id** do tarefa desejada
    2. body: { "descricao": "DESCRICAO_TAREFA", "usuario_id": "USUARIO_ID_TAREFA" , "categoria_id": "CATEGORIA_ID_TAREFA" }
    3. return: Dados atualizados da tarefa **id** , **nome** , **usuario_id**, **categoria_id** , **usuario** (**id** , **nome**, **email**, **login**, **senha**), categoria( **id** , **nome** , **usuario_id** )

5.  `DELETE /tarefas/:id`
    1. parametro: **id** da tarefa que desejado excluir
    2. body: NULL
    3. return: **Tarefa excluída**, caso true. **Erro ao excluir tarefa**, case false