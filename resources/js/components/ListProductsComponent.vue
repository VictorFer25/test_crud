<template>
  <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">LISTA DE PRODUCTOS</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" @click="downloadExcel">Expotar a Excel</button>
                            </div>
                        </div>
                        <div class="row mt-3" v-if="show_table">
                            <div class="col-12">
                                <v-server-table ref="table" :url="url"  :columns="columns" :options="options">
                                    <template v-slot:acciones="{row}">
                                        <div>
                                            <button  class="btn btn-danger btn-sm" @click="deleteRow(row)">
                                                Eliminar
                                            </button>
                                        </div>
                                    </template>
                                </v-server-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            //config_table
            url: '/productos/lista',
            columns:['clave','categoria','producto','precio' ,'acciones'],
            options: {
                sortIcon: {
                    base : 'fa',
                    is: 'fa-sort',
                    up: 'fa-sort-up',
                    down: 'fa-sort-down'
                },
                headings: {
                    clave: 'Clave',
                    categoria: 'Categoria',
                    producto: 'Descripción',
                    precio: 'Precio',
                    acciones: '',
                },
                texts: {
                    count: 'MOSTRANDO DE {from} A {to} DE {count} REGISTROS|SE ENCONTRARÓN {count} REGISTROS | SE ENCONTRÓ UN REGISTRO',
                    filter: 'BUSCAR:',
                    filterPlaceholder: 'BUSCAR...',
                    limit: 'REGISTROS:',
                    recordsDisplay: 'REGISTROS',
                    page: 'PÁGINAS:',
                    noResults: 'NO HAY REGISTROS',
                    filterBy: 'BUSCAR...',
                    loading: 'CARGANDO...',
                    defaultOption: 'SELECCIONAR {column}'
                },
                filterByColumn: true,
                filterable: ['clave','categoria','producto'],
                resizableColumns: false,
                sendEmptyFilters: false,
                childRowTogglerFirst: true,
                showChildRowToggler: true,
                perPage: 5,
                perPageValues: [5, 10, 15, 20],
                requestAdapter: function(data) {
                    return {
                        limit:      data.limit,
                        page:       data.page,
                        query:      data.query,
                        ascending:  data.ascending,
                        byColumn:   data.byColumn,
                        orderBy:    data.orderBy,
                        sort: data.orderBy ? data.orderBy : 'clave',
                        direction: data.ascending ? 'asc' : 'desc',
                    }
                },
                responseAdapter: function(resp) {
                    var data = this.getResponseData(resp);
                    try{
                        return {
                            data: data.data,
                            count: data.count

                        };
                    }catch(error){
                        toastr.error(error);
                    }
                },
            },
            show_table:false,

        }
    },
    mounted(){
        this.loadTable();
    },
    methods:{
        loadTable(){
            this.show_table = true;
        },
        async deleteRow(row){
            try{
                let response = await Swal.fire({
                    icon: 'question',
                    title: 'CONFIRMAR',
                    html: '<p> ¿Desea eliminar el producto? </p>',
                    confirmButtonText: 'ACEPTAR',
                    showCancelButton: true,
                    cancelButtonText: 'CANCELAR',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    reverseButtons: true
                });

                if(!response.value){ return ;}

                this.$spinner();

                let resp  = await axios.delete(`/productos/${row.id}/destroy`).then((res)=>res.data);
                Swal.close();
                if(!resp.status){
                    return Swal.fire({
                        icon: 'error',
                        html: `<p> ${resp.msg} </p>`,
                        confirmButtonText: 'ACEPTAR',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        allowEnterKey: false,
                    })
                }

                 Swal.fire({
                    icon: 'success',
                    html: `<p> El producto se eliminó con éxito </p>`,
                    confirmButtonText: 'ACEPTAR',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                })

                this.$refs.table.refresh();

            }catch(err){
                Swal.close();
                Swal.fire({
                    icon:'error',
                    html:'<p> Lo sentimos, ha ocurrido un error interno </p>',
                    confirmButtonText: 'ACEPTAR',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                })
            }
        },

        downloadExcel(){
            let form = $("<form>").attr({
                target:"_blank",
                action:"/productos/download-excel",
                method:"POST",
                id:"form1"
            });

            let token = $("<input>").attr({name:"_token",type:"text",value: $($("meta[name=csrf-token]")[0]).attr("content")});

            form.append( token);
            $(document.body).append(form);
            form.submit();
            $("#form1").remove();
        }
    }
}
</script>

<style>

</style>
