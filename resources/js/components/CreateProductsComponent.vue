<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">REGISTRO DE PRODUCTOS</div>

                    <div class="card-body">
                        <ValidationObserver ref="form">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <label for="">Clave</label>
                                            <input type="text" class="form-control" placeholder="Ingrese la clave" v-model="key">
                                            <span id="error" class="text-danger">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <label for="">Categoria</label>
                                            <input type="text" class="form-control" placeholder="Ingrese la categoria" v-model="category">
                                            <span id="error" class="text-danger">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <label for="">Producto</label>
                                            <input type="text" class="form-control" placeholder="Ingrese el nombre del producto" v-model="product">
                                            <span id="error" class="text-danger">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <label for="">Precio</label>
                                            <input type="text" class="form-control" placeholder="Ingrese el precio" v-model="price">
                                            <span id="error" class="text-danger">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mt-4">
                                        <button class="btn btn-success" @click="add">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </ValidationObserver>
                        <div class="row" v-if="records.length < 1">
                            <div class="col-12">
                                <div class="alert alert-info text-center" role="alert">
                                   Ingrese un producto para empezar a cargar la tabla
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-12">
                                <v-client-table :data="records" :columns="columns" :options="options">
                                    <template v-slot:acciones="{row}">
                                        <div>
                                            <button  class="btn btn-danger btn-sm" @click="deleteRow(row)">
                                                Eliminar
                                            </button>
                                        </div>
                                    </template>
                                </v-client-table>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary" @click="saveRecords">Guardar</button>
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
                //config-table
                columns: ['clave', 'categoria', 'producto','precio','acciones'],
                options: {
                    headings: {
                        clave: 'Clave',
                        categoria: 'Categoria',
                        producto: 'Producto',
                        precio: 'Precio',
                        acciones: '',
                    },
                    filterByColumn: true,
                    filterable: ['clave','categoria','producto'],
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
                },

                show_table:false,

                records:[],

                //form
                key:null,
                category:null,
                product:null,
                price:null,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            resetVars(){
                this.key = null;
                this.category = null;
                this.product = null;
                this.price = null;
                this.$refs.form.reset()
            },
            async add(){
                try{
                    let validate = await this.$refs.form.validate();

                    if(!validate){
                        return ;
                    }


                    let data = {
                        id :Date.now(),
                        clave:this.key,
                        categoria:this.category,
                        producto:this.product,
                        precio:this.price
                    }

                    this.records.push(data);

                    this.resetVars();

                }catch(err){

                }
            },
            deleteRow(row){
                let index = this.records.findIndex((el)=> el.id == row.id)

                this.records.splice(index,1);
            },
            async saveRecords(){
                try{
                    if(this.records.length < 1){
                        //no se puede guardar sin registros
                        return ;
                    }

                    // let resp = this.$axios
                }catch(err){

                }
            }
        }
    }
</script>
