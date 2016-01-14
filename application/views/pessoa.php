<!-- Grid -->
<main class="container view-grid">
    <ol class="breadcrumb">
      <li><a href="#">Cliente</a></li>
      <li><a href="#" class="active">Listagem de cliente</a></li>
    </ol>
    
    <form action="pessoa" class="form-horizontal" id="form_pessoa_consultar">
        <div class="form-group">
            <div class="col-md-2 col-xs-4">
                <select id="filtrp" name="filtro" class="form-control" data-size="5">
                    <option value="1" >Nome</option>
                    <option value="2" >E-mail</option>
                    <option value="3" >Telefone</option>
                </select>
            </div>
            
            <div class="col-md-8 col-xs-8">
                <div class="input-group">
                    <input name="expressao" id="expressao" class="form-control"  placeholder="Consultar" />
                    <span class="input-group-btn">
                        <a class="btn btn-default pesquisar" >
                            <span class="glyphicon glyphicon-search"></span>
                            Consultar
                        </a>
                        <a href="#" class="btn btn-primary" >
                            <span class="glyphicon glyphicon-plus">
                            </span> 
                            Novo
                        </a>                             
                    </span>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Lista de pessoas -->
    <table class="table table-hover table-bordered" >
        <thead>
            <tr>
                <th class="col-md-4">Nome</th>
                <th class="col-md-3">E-mail</th>
                <th class="col-md-3">Telefone</th>
                <th class="col-md-2">Opções</th>
            </tr>
        </thead>
        <tbody class="lista-disciplina"></tbody>
    </table>
</main>
<!-- Fim Grid -->

<!-- Form -->
<main class="container view-form hidden">
    <ol class="breadcrumb">
      <li><a href="#">Cliente</a></li>
      <li><a href="#" class="active">Cadastro</a></li>
    </ol>
</main>
<!-- Fim Form -->