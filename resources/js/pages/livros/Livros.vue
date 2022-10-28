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
						<th v-for="col in columns">
							{{ col.label }}
						</th>
					</thead>
					<tbody class="table-group-divider">
						<tr v-for="item in data">
							<td v-for="col in columns" class="px-0" :style="col.style">
								<span v-if="col.type !== 'actions'">{{ item[col.key] }}</span>
								<div v-else class="d-flex">
									<button type="button" class="btn btn-primary" @click="editar(item.id)">
										<i class="fa fa-pencil"></i>
									</button>
									<!-- <button type="button" class="btn btn-danger" @click="excluir(item.id)">
                                        <i class="bi bi-trash"></i>
                                    </button> -->
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
		<LivrosForm></LivrosForm>
	</div>
</template>

<script>
import { loadingMixin } from '../mixins/loadingMixin';
import Loading from '../components/Loading.vue';
import LivrosForm from './LivrosForm.vue';
import livrosService from '../../services/livros.service';

export default {
	name: 'LivrosIndex',
	mixins: [loadingMixin],
	components: {
		Loading,
		LivrosForm,
	},
	data: () => ({
		search: '',
		pages: [],
		columns: [
			// {key: 'id', label: 'ID', type: 'text'},
			{ key: "titulo", label: "Título", type: "string", style: { "font-weight": "bold" } },
			{ key: "ano", label: "Ano", type: "number" },
			{ key: "edicao", label: "Edição", type: "number" },
			{ key: "editora", label: "Editora", type: "string" },
			{ key: "autores", label: "Autores", type: "string" },
			{ key: "assunto", label: "Assunto", type: "string" },
			{ key: "quantidade", label: "Quantidade", type: "number" },
			{ key: 'actions', label: 'Ações', type: 'actions' }
		],
		data: [],
		title: 'Livros'
	}),
	async mounted() {
		this.getData()
		this.$emitter.on('livros.reload', this.getData)
	},
	methods: {
		novo() {
			this.$emitter.emit('livros.create')
		},
		editar(item) {
			this.$emitter.emit('livros.edit', item)
		},
		getData(page = 1) {
			this.startLoading()
			livrosService.getLivros({
				page: page,
				q: this.search || null
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
			if (page.disabled) return
			const pageNumber = page.url.match(/page=(\d+)/)[1]
			this.getData(pageNumber)
		},
		clearSearch() {
			this.search = ''
			this.getData()
		}
	}
}
</script>
