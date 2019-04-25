<template>
  <!-- Contenido Principal -->
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Articulos
          <button
            type="button"
            @click="abrirModal('articulo','registrar')"
            class="btn btn-secondary"
          >
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
                <input
                  v-model="buscar"
                  @keyup.enter="listarArticulo(1,buscar,criterio)"
                  type="text"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button
                  type="submit"
                  @click="listarArticulo(1,buscar,criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('articulo','actualizar',articulo)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <template v-if="articulo.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivarCategoria(articulo.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarCategoria(articulo.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
                <td v-text="articulo.codigo"></td>
                <td v-text="articulo.nombre"></td>
                <td v-text="articulo.nombre_categoria"></td>
                <td v-text="articulo.precio_venta"></td>
                <td v-text="articulo.stock"></td>
                <td v-text="articulo.descripcion"></td>
                <td>
                  <div v-if="articulo.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page>1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,buscar,criterio)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      :class="{'mostrar':modal}"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="idcategoria">
                    <option value="0">Seleccione</option>
                    <option
                      v-for="categoria in arrayCategoria"
                      :key="categoria.id"
                      :value="categoria.id"
                      v-text="categoria.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Codigo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="codigo"
                    class="form-control"
                    placeholder="Ingrese el codigo de barras"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Nombre de Articulo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Ingrese el nombre del articulo"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Precio</label>
                <div class="col-md-9">
                  <input type="number" v-model="precio_venta" class="form-control" placeholder="$">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Stock</label>
                <div class="col-md-9">
                  <input type="number" v-model="stock" class="form-control" placeholder>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Ingrese descripcion"
                  >
                </div>
              </div>
              <div v-show="errorArticulo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              @click="registrarArticulo()"
              class="btn btn-primary"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              @click="actualizarArticulo()"
              class="btn btn-primary"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
  <!-- /Fin del contenido principal -->
</template>

<script>
export default {
  data() {
    return {
      articulo_id: 0,
      idcategoria: 0,
      nombre_categoria: "",
      codigo: "",
      nombre: "",
      precio_venta: 0,
      stock: 0,
      descripcion: "",
      arrayArticulo: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorArticulo: 0,
      errorMostrarMsjArticulo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: "",
      arrayCategoria: []
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }

      return pagesArray;
    }
  },
  methods: {
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualizar pagina actual
      me.pagination.current_page = page;
      //Enviar la peticion para visualizar la data
      me.listarArticulo(page, buscar, criterio);
    },
    listarArticulo(page, buscar, criterio) {
      let me = this;
      var url =
        "/articulo?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          // handle success
          var respuesta = response.data;
          me.arrayArticulo = respuesta.articulos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
    registrarArticulo() {
      if (this.validarArticulo()) {
        return;
      }
      let me = this;
      axios
        .post("/articulo/registrar", {
          idcategoria : this.idcategoria,
          codigo: this.codigo,
          nombre: this.nombre,
          stock: this.stock,
          precio_venta: this.precio_venta,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarArticulo(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarArticulo() {
      this.errorArticulo = 0;
      this.errorMostrarMsjArticulo = [];

      if (!this.nombre)
        this.errorMostrarMsjArticulo.push("El nombre del articulo no puede estar vacio");
      if (this.idcategoria == 0)
        this.errorMostrarMsjArticulo.push("Seleccione una categoria");
      if (!this.stock)
        this.errorMostrarMsjArticulo.push("Agregue un stock");
      if (this.precio_venta == 0)
        this.errorMostrarMsjArticulo.push("El precio venta del articulo debe ser un numero");

      if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

      return this.errorArticulo;
    },
    activarCategoria(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons
        .fire({
          title: "Estas seguro de activarlo?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si,activar!",
          cancelButtonText: "No, cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/categoria/activar", {
                id: id
              })
              .then(function(response) {
                me.listarArticulo(1, "", "nombre");
                swalWithBootstrapButtons.fire(
                  "Activado!",
                  "El regristro esta activado!",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire("Cancelled", "", "error");
          }
        });
    },
    desactivarCategoria(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons
        .fire({
          title: "Estas seguro?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si,desactivar!",
          cancelButtonText: "No, cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/categoria/desactivar", {
                id: id
              })
              .then(function(response) {
                me.listarArticulo(1, "", "nombre");
                swalWithBootstrapButtons.fire(
                  "Desactivado!",
                  "El registro se a desactivado!",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire("Cancelled", "", "error");
          }
        });
    },
    actualizarArticulo() {
      if (this.validarArticulo()) {
        return;
      }
      let me = this;
      axios
        .put("/articulo/actualizar", {
          idcategoria : this.idcategoria,
          codigo: this.codigo,
          nombre: this.nombre,
          stock: this.stock,
          precio_venta: this.precio_venta,
          descripcion: this.descripcion,
          id:this.articulo_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarArticulo(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "articulo": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Articulo";
              this.idcategoria = 0;
              this.nombre_categoria = "";
              this.codigo = '';
              this.nombre = '';
              this.precio_venta = 0;
              this.stock = 0;
              this.descripcion = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Articulo";
              this.tipoAccion = 2;
              this.articulo_id = data["id"];
              this.idcategoria = data["idcategoria"];
              this.codigo = data["codigo"];
              this.nombre = data["nombre"];
              this.stock = data["stock"];
              this.precio_venta = data["precio_venta"];
              this.descripcion = data["descripcion"];
              break;
            }
          }
        }
      }
      this.selectCategoria();
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.idcategoria = 0;
      this.nombre_categoria = "";
      this.codigo = "";
      this.nombre = "";
      this.precio_venta = 0;
      this.stock = 0;
      this.descripcion = "";
      this.errorArticulo = 0;
    },
    selectCategoria() {
      let me = this;
      var url = "/categoria/selectCategoria";
      axios
        .get(url)
        .then(function(response) {
          // handle success
          var respuesta = response.data;
          me.arrayCategoria = respuesta.categorias;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    }
  },
  mounted() {
    this.listarArticulo(1, this.buscar, this.criterio);
  }
};
</script>

<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red;
  font-weight: bold;
}
</style>

