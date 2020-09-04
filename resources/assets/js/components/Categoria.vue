<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="searchText" @keyup.enter="listarCategorias(criterio,searchText)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCategorias(criterio,searchText)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"  
                                        @click="abrirModal('actualizar',categoria)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" v-if="categoria.condicion"  @click='desactivar(categoria.id)' class="btn btn-danger btn-sm">
                                          <i class="icon-trash"></i>
                                        </button>
                                        <button type="button" v-else @click='activar(categoria.id)' class="btn btn-info btn-sm">
                                          <i class="icon-check"></i>
                                        </button>

                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                        <div v-if="categoria.condicion">
                                            <span class="badge badge-success" >Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger" >Inactivo</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#">Ant</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" :class="{'mostrar' : modal}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="'display': none" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text='tituloModal'></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 text-center">
                                        <span class="text-danger" v-text="msg"></span>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAction==1" class="btn btn-primary" @click="guardarCategoria()">Guardar</button>
                            <button type="button" v-if="tipoAction==2" class="btn btn-info" @click="actualizarCategoria()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
        </main>
</template>

<script>
    export default {
       data(){
           return {
               id : '',
               nombre : '',
               descripcion : '',
               arrayCategoria : [],
               tipoAction : 1,
               searchText : '',
               criterio : 'nombre',
               msg : '',
               modal : 0,
               tituloModal : ''
           }
       },
       methods: {
           listarCategorias(criterio,searchText){
               let url=`/categoria?criterio=${criterio}&searchText=${searchText}`;
               axios.get(url)
                    .then((response) => {
                        // handle success
                        
                        this.arrayCategoria = response.data
                        
                    })
                    .catch((error) => {
                        // handle error
                        console.log(error);
                    })
           },
           cerrarModal(){
               this.modal = 0;
               this.tituloModal = '';
               this.nombre = '';
               this.descripcion = '';
           },
           abrirModal(accion,data = []){
               switch (accion) {
                   case 'registrar':
                       this.modal = 1;
                       this.tipoAction = 1;
                       this.tituloModal = 'Registrar Categoria';
                       // this.id = '';
                       this.nombre = '';
                       this.descripcion = '';
                       break;
                   case 'actulizar':
                       this.modal = 1;
                       this.tipoAction = 2;
                       this.tituloModal = 'Actualizar Categoria';
                       this.id = data.id;
                       this.nombre = data.nombre;
                       this.descripcion = data.descripcion;
                       break;
                   default:
                       break;
               }
           },
           guardarCategoria(){
               if (this.nombre == '') {
                   this.msg = 'Debe ingresar el nombre de la categoria';
                   return;
               }
               axios.post('/categoria/registrar', {
                        'nombre': this.nombre,
                        'descripcion': this.descripcion
                    })
                    .then((response) => {
                        console.log(response);
                        this.listarCategorias('nombre','');
                        this.tipoAction = 1;
                        this.modal.modal('hide');
                    })
                    .catch((error) => {
                        console.log(error);
                    });
           },
           editarCategoria(categoria){
               this.nombre=categoria.nombre;
               this.descripcion = categoria.descripcion;
               this.id = categoria.id;
               this.tipoAction= 2;
           },
            actualizarCategoria(){
                axios.put('/categoria/actualizar', {
                        'nombre': this.nombre,
                        'descripcion': this.descripcion,
                        'id' : this.id
                    })
                    .then((response) => {
                        console.log(response);
                        this.listarCategorias('nombre','');
                        this.tipoAction = 1;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            desactivar(id){
                Swal.fire({
                    title: 'Estas seguro de querer desactivar la Categoria?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        
                    if (result.value) {
                        axios.put('/categoria/desactivar', {
                                'id' : id
                            })
                            .then((response) => {
                                //console.log(response);
                                this.listarCategorias('nombre','');
                                })
                            .catch((error) => {
                                console.log(error);
                            });
                                    Swal.fire(
                                    'Desactivado!',
                                    'La Categoria fue Desactivada',
                                    'success'
                                    )
                                    
                                }
                })
            },
            activar(id){
                Swal.fire({
                    title: 'Estas seguro de querer Activar la Categoria?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {

                    if (result.value) {
                        axios.put('/categoria/activar', {
                                'id' : id
                            })
                            .then((response) => {
                                //console.log(response);
                                this.listarCategorias('nombre','');
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                                    
                                    Swal.fire(
                                    'Activado!',
                                    'La categoria fue Activada',
                                    'success'
                                    )
                                    
                    }
                })
            },
            

       },
        mounted() {
            this.listarCategorias(this.criterio,this.searchText);
        }
    }
</script>
<style>
.modal-content{
    width: 100%;
    position: absolute;
}
.mostrar{
    display : list-item !important;
    opacity : 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
</style>