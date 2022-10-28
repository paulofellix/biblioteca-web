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
								<div v-if="col.key === 'actions'" class="d-flex">
									<button type="button" class="btn btn-primary" @click="editar(item.id)" data-bs-toggle="tooltip"
										data-bs-title="Editar">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="btn btn-success ms-2" @click="devolver(item.id)" data-bs-toggle="tooltip"
										data-bs-title="Confirmar devolução" v-if="!item.data_devolucao">
										<i class="fa fa-check"></i>
									</button>
								</div>
								<div class="d-flex" v-else>
									<template v-if="col.key === 'status'">
										<span v-if="item[col.key] === 'A'" data-bs-toggle="tooltip" data-bs-title="Livro atrasado">
											<i class="text-danger fs-3 fa fa-circle"></i>
										</span>
										<span v-else-if="item[col.key] === 'E'" data-bs-toggle="tooltip" data-bs-title="Em dias">
											<i class="text-success fs-3 fa fa-circle"></i>
										</span>
										<span v-else>
											<i class="text-secondary fs-3 fa fa-circle" data-bs-toggle="tooltip"
												data-bs-title="Devolvido"></i>
										</span>
									</template>
									<template v-else>
										{{ item[col.key] }}
									</template>
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
		<EmprestimosForm></EmprestimosForm>
	</div>
</template>

<script>
import { loadingMixin } from '../mixins/loadingMixin';
import Loading from '../components/Loading.vue';
import EmprestimosForm from './EmprestimosForm.vue';
import emprestimosService from '../../services/emprestimos.service';
import Swal from 'sweetalert2'
import dayjs from 'dayjs';

export default {
	name: 'EmprestimosIndex',
	mixins: [loadingMixin],
	components: {
		Loading,
		EmprestimosForm,
	},
	data: () => ({
		search: '',
		pages: [],
		columns: [
			// {key: 'id', label: 'ID', type: 'text'},
			{ key: "pessoa_tipo", label: "Tipo", type: "string" },
			{ key: "pessoa_nome", label: "Nome", type: "string", style: { "font-weight": "bold" } },
			{ key: "livro_titulo", label: "Livro", type: "string", style: { "font-weight": "bold" } },
			{ key: "data_emprestimo_formatada", label: "Data Empréstimo", type: "string" },
			{ key: "data_previsao_devolucao", label: "Data Prevista Devolução", type: "string" },
			{ key: "data_devolucao_formatada", label: "Data Devolução", type: "string" },
			{ key: "status", label: "Status", type: "string" },
			{ key: 'actions', label: 'Ações', type: 'actions' }
		],
		data: [],
		title: 'Empréstimos',
	}),
	async mounted() {
		this.getData()
		this.$emitter.on('emprestimos.reload', this.getData)
	},
	watch: {

	},
	methods: {
		novo() {
			this.$emitter.emit('emprestimos.create')
		},
		editar(item) {
			this.$emitter.emit('emprestimos.edit', item)
		},
		getData(page = 1) {
			this.startLoading()
			emprestimosService.getEmprestimos({
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
				})
				.catch(error => {
					console.log(error);
				}).finally(() => {
					this.stopLoading()
					this.$nextTick(() => {
						this.enableTooltips()
					})
				})
		},
		paginate(page) {
			if (page.disabled) return
			const pageNumber = page.url.match(/page=(\d+)/)[1]
			this.getData(pageNumber)
		},
		clearSearch() {
			this.search = ''
			this.getData()
		},
		enableTooltips() {
			const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			})
		},
		devolver(id) {
			Swal.fire({
				icon: 'question',
				cancelButtonText: 'Cancelar',
				confirmButtonText: 'Sim',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				title: 'Deseja devolver o livro?',
				text: 'O livro será devolvido e o empréstimo será encerrado.',
				focusCancel: true
			}).then(async (result) => {
				if (result.isConfirmed) {
					try {
						const { data } = await emprestimosService.updateEmprestimo(id, {
							id,
							data_devolucao: dayjs().format('YYYY-MM-DD')
						})
						Swal.fire({
							icon: 'success',
							title: 'Livro devolvido com sucesso!',
							showConfirmButton: true,
							timer: 1500,
							timerProgressBar: true,
						}).then(() => {
							this.getData()
						})
					} catch (err) {
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Algo deu errado!',
							showConfirmButton: true,
						})
					}
				}
			})
		}
	}
}
</script>
