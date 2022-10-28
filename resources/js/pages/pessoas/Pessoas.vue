<template>
	<div class="container pt-4 pb-2">
		<Loading :loading="loading"></Loading>
		<div class="mt-4" v-if="!loading">
			<div>
				<div class="row align-items-center mb-4">
					<div class="col">
						<div class="d-flex">
							<span class="fs-3">{{ title }}</span>
							<button type="button" class="ms-2 btn btn-primary" @click="novo">
								<strong>Novo</strong>
							</button>
						</div>
					</div>
					<div class="col-6">
						<div class="input-group mt-1">
							<span class="input-group-text"><i class="fa fa-search"></i></span>
							<input type="text" class="form-control" placeholder="Pesquisar" v-model="search" @keyup.enter="getData()">
							<span style="cursor: pointer;" class="input-group-text" v-if="search" @click="clearSearch"><i
									class="fa fa-times"></i></span>
							<button class="btn btn-outline-primary" type="button" @click="getData()">Pesquisar</button>
						</div>
					</div>
				</div>
				<table class="table table-striped" v-if="!loading">
					<thead>
						<th v-for="col in tableColumns">
							{{ col.label }}
						</th>
					</thead>
					<tbody class="table-group-divider">
						<tr v-for="item in data">
							<td v-for="col in tableColumns" class="px-0" :style="col.style">
								<span v-if="col.type !== 'actions'">{{ item[col.key] }}</span>
								<div v-else class="d-flex">
									<button type="button" class="btn btn-primary" @click="editar(item.id)">
										<i class="fa fa-pencil"></i>
									</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<nav class="mb-5">
					<ul class="pagination justify-content-end">
						<li class="page-item" v-for="page in pages">
							<a class="page-link" :class="{ active: page.active, disabled: page.disabled }" href="#"
								@click="paginate(page)">{{ page.label }}</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<PessoasForm v-if="tipoForm" :tipoForm="tipoForm"></PessoasForm>
	</div>
</template>

<script>
import { loadingMixin } from '../mixins/loadingMixin';
import Loading from '../components/Loading.vue';
import pessoasService from '../../services/pessoas.service';
import PessoasForm from './PessoasForm.vue';

export default {
	name: 'PessoasIndex',
	mixins: [loadingMixin],
	components: {
		Loading,
		PessoasForm
	},
	data: () => ({
		title: 'Pessoas',
		search: '',
		pages: [],
		columns: [
			// {key: 'id', label: 'ID', type: 'text'},
			{ key: "matricula", label: "Matricula", type: "number", tipoForm: "A" },
			{ key: "titulacao", label: "Titulacao", type: "number", tipoForm: "P" },
			{ key: "nome", label: "Nome", type: "string", tipoForm: "T", style: { "font-weight": "bold" } },
			{ key: "cpf", label: "CPF", type: "string", tipoForm: "T" },
			{ key: "rg", label: "RG", type: "string", tipoForm: "T" },
			{ key: "telefone", label: "Telefone", type: "string", tipoForm: "T" },
			{ key: "celular", label: "Celular", type: "string", tipoForm: "T" },
			{ key: "email", label: "Email", type: "string", tipoForm: "T" },
			// {key: "endereco", label: "Endereço", type: "string", tipoForm: "T"},
			{ key: 'actions', label: 'Ações', type: 'actions', tipoForm: 'T' }
		],
		data: [],
		tipoForm: null,
		editPessoa: false,
		editKey: 0
	}),
	async mounted() {
		this.updateMetaFields()
		this.getData()
		this.$emitter.on('pessoas.reload', this.getData)
	},
	updated() {
		this.updateMetaFields()
	},
	watch: {
		'$route': {
			handler: function (val, oldVal) {
				if (val.path != oldVal.path) {
					this.search = ''
					this.updateMetaFields()
					this.getData()
				}
			},
			deep: true
		}
	},
	computed: {
		tableColumns() {
			return this.columns.filter(k => [this.tipoForm, 'T'].includes(k.tipoForm))
		}
	},
	methods: {
		novo() {
			this.$emitter.emit('pessoas.create')
		},
		editar(item) {
			this.editPessoa = true
			this.$nextTick(() => {
				this.$emitter.emit('pessoas.edit', item)
			})
		},
		getData(page = 1) {
			if (this.tipoForm === null) {
				return
			}
			this.startLoading()
			pessoasService.getPessoas({
				page: page,
				q: this.search || null,
				tipo: this.tipoForm || null
			})
				.then(({ data }) => {
					this.data = data.data
					this.pages = data.links.map((k, i) => {
						if (!k.url) k.disabled = true
						if (i === 0) k.label = 'Anterior'
						if (i === data.links.length - 1) k.label = 'Próximo'
						return k
					})
					this.stopLoading()
				})
				.catch(error => {
					console.log(error);
				});
		},
		paginate(page) {
			if (page.active || page.disabled) return
			const pageNumber = page.url.match(/page=(\d+)/)[1]
			this.getData(pageNumber)
		},
		clearSearch() {
			this.search = ''
			this.getData()
		},
		updateMetaFields() {
			this.title = this.$route.meta.title
			if (this.$route.meta.resource === 'alunos') {
				this.tipoForm = 'A'
			} else if (this.$route.meta.resource === 'professores') {
				this.tipoForm = 'P'
			}
		}
	}
}
</script>
