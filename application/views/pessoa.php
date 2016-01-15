<!-- Grid -->
<main class="container view-grid">
    <ol class="breadcrumb">
      <li><a href="javascript:;" class="active">Clientes</a></li>
    </ol>
    
    <?php echo form_open('pessoa', ['id' => 'form_pessoa_consultar', 'class' => 'form-horizontal']);?>
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
                        <a href="javascript:consultar('#form_pessoa_consultar', 'read', 'html', function() {}, retornoConsulta);" class="btn btn-default pesquisar" >
                            <span class="glyphicon glyphicon-search"></span>
                            Consultar
                        </a>
                        <a href="javascript:showViewForm();" class="btn btn-primary" >
                            <span class="glyphicon glyphicon-plus">
                            </span> 
                            Novo
                        </a>                             
                    </span>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>    
    
    <!-- Lista de pessoas -->
   <div id="resposta_consulta"></div>
</main>
<!-- Fim Grid -->

<!-- Form -->
<main class="container view-form hidden">
    <ol class="breadcrumb">
      <li><a href="javascript:showViewGrid();">Cliente</a></li>
      <li><a href="javascript:;" class="active">Cadastro</a></li>
    </ol>
    

    <?php echo form_open('pessoa', ['id' => 'form_pessoa']);?>
    <fieldset>
        <legend>Tipo de Cadastro</legend>
    </fieldset>    
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo" class="req">Tipo de Cadastro:</label>
                <br>
                <input name="tipo" value="F" required type="radio" /> Pessoa Fisíca  &nbsp;
                <input name="tipo" value="J" required type="radio" /> Pessoa Jurídica 
            </div>
            <b><label for="tipo" class="error"></label></b>
        </div>            

        <div class="col-md-6">
            <div class="form-group">
                <label for="categoria" class="req">Categoria:</label>
                <br>
                <input name="categoria" value="0" required type="radio" /> Funcionário  &nbsp;
                <input name="categoria" value="1" required type="radio" /> Fornecedor  &nbsp;
                <input name="categoria" value="2" required type="radio" /> Cliente
            </div>
            <b><label for="categoria" class="error"></label></b>
        </div>            
    </div>    
    
    <fieldset>
        <legend>Cadastrar Cliente</legend>
    </fieldset>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="nome" class="req">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome">
            </div>
        </div>        
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf">
            </div>
        </div>        
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="sexo" class="req">Sexo: </label>
                <br>
                <input name="sexo" value="M" required type="radio" /> Masculino &nbsp;
                <input name="sexo" value="F" required type="radio" /> Feminino 
            </div>
            <b><label for="sexo" class="error"></label></b>
        </div>        
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="nascimento" >Nascimento:</label>
                <input type="date" class="form-control" id="nascimento" name="nascimento">
            </div>
        </div>
    </div>    
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="email" class="req">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
        </div> 
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="telefone" class="req">Telefone:</label>
                <input type="tel" class="form-control" id="telefone" data-mask="(99) 9999-9999?" name="telefone">
            </div>
        </div>        
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="celular" class="req">Celular:</label>
                <input type="tel" class="form-control" id="celular" data-mask="(99) 9999-9999?" name="celular">
            </div>
        </div>        
    </div>
    
    <fieldset>
        <legend>Endereço</legend>
    </fieldset>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="endereco" class="req">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco">
            </div>
        </div>        
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="complemento">Complemento:</label>
                <input type="text" class="form-control" id="complemento" name="complemento">
            </div>
        </div>        
    </div>    
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="bairro" class="req">Bairro:</label>
                <input type="text" class="form-control" id="bairro" name="bairro">
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" class="form-control" data-mask="999999-99" id="cep" name="cep">
            </div>
        </div> 
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="telefone" class="req">Estado:</label>
                <select class="form-control" id="estado" name="estado"><option></option><?php echo $estados; ?></select>
            </div>
        </div>        
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="cidade" class="req">Cidade:</label>
                <select class="form-control" id="cidade" name="cidade"></select>
            </div>
        </div>        
    </div>
    <hr>
    <?php echo form_close(); ?>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <a href="javascript:;" id="salvar-pessoa" class="btn btn-primary">Salvar</a>
                <a href="javascript:showViewGrid();" class="btn btn-default">Voltar</a>
            </div>
        </div>
    </div>
</main>
<!-- Fim Form -->

<!-- Javascripts -->
<script src="<?php echo base_url();?>assets/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo base_url();?>assets/jquery-validation/messages_pt_BR.min.js"></script>
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap-inputmask.min.js "></script>
<script src="<?php echo base_url();?>assets/js/principal.js"></script>
<script src="<?php echo base_url();?>assets/js/crud.js"></script>
<script src="<?php echo base_url();?>assets/js/notificacao.js"></script>
<script src="<?php echo base_url();?>assets/js/notificacao.js"></script>
<script src="<?php echo base_url();?>assets/js/pessoa.js"></script>
<!-- Fim Javascripts -->