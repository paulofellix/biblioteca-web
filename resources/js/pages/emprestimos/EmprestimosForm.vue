<template>
	<div>
		<div class="modal fade" id="editEmprestimos">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<VeeForm v-slot="{ handleSubmit, errors }" :validation-schema="schema" as="div" class="modal-content"
					ref="veeForm">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">{{ modalTitle }}</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form @submit.prevent="handleSubmit($event, onSubmit)" id="editForm" ref="formEl">
							<div class="row mb-3">
								<label for="id" class="col-form-label col-sm-2">ID</label>
								<div class="col-sm-10">
									<Field type="text" class="form-control" name="id" disabled v-model="form.id" :standalone="true" />
								</div>
							</div>
							<div class="row mb-3">
								<label for="pessoa" class="col-form-label col-sm-2">Pessoa</label>
								<div class="col-sm-10 dropdown" ref="dropdownPessoa">
									<div class="input-group">
										<input type="text" class="form-control" :class="{ 'is-invalid': errors.pessoa && modalReady }"
											ref="inputPessoa" name="pessoa_search" :standalone="true"
											:disabled="searchPessoaObj.selected || !!this.form.id" v-model="searchPessoaObj.value"
											@focus="onEnterPessoa" @blur="onBlurPessoa" v-debounce:150ms.cancelonempty="searchPessoas" />
										<button class="btn btn-danger" v-if="searchPessoaObj.selected && !this.form.id"
											@click="clearPessoa">
											<i class="fa fa-times"></i>
										</button>
										<ErrorMessage v-if="errors.pessoa" name="pessoa" as="div" class="invalid-feedback" />
									</div>
									<ul class="dropdown-menu" aria-labelledby="dropdownPessoa">
										<template v-if="searchPessoaObj.results.length > 0">
											<li v-for="pessoa in searchPessoaObj.results" :key="pessoa.id">
												<a class=" dropdown-item" href="#" @click="selectPessoa(pessoa)">
													{{ pessoa.nome }}
												</a>
											</li>
										</template>
										<template v-else>
											<li v-for="pessoa in searchPessoaObj.results" :key="pessoa.id">
												<span class=" dropdown-item-text">
													Nenhum resultado encontrado
												</span>
											</li>
										</template>
									</ul>
								</div>
							</div>
							<div class="row mb-3">
								<label for="livro" class="col-form-label col-sm-2">Livro</label>
								<div class="col-sm-10 dropdown" ref="dropdownLivro">
									<div class="input-group">
										<input type="text" class="form-control" :class="{ 'is-invalid': errors.livro && modalReady }"
											ref="inputLivro" name="livro_search" :standalone="true"
											:disabled="searchLivroObj.selected || !!this.form.id" v-model="searchLivroObj.value"
											@focus="onEnterLivro" @blur="onBlurLivro" v-debounce:150ms="searchLivros" />
										<button class="btn btn-danger" v-if="searchLivroObj.selected && !this.form.id" @click="clearLivro">
											<i class="fa fa-times"></i>
										</button>
										<ErrorMessage v-if="errors.livro" name="livro" as="div" class="invalid-feedback" />
									</div>
									<ul class="dropdown-menu" aria-labelledby="dropdownLivro">
										<template v-if="searchLivroObj.results.length > 0">
											<li v-for="livro in searchLivroObj.results" :key="livro.id">
												<span class="dropdown-item-text text-muted" v-if="livro.disabled">
													{{ livro.titulo }}
												</span>
												<a class=" dropdown-item" href="#" @click="selectLivro(livro)" v-else>
													{{ livro.titulo }}
												</a>
											</li>
										</template>
										<template v-else>
											<li>
												<span class=" dropdown-item-text">
													Nenhum resultado encontrado
												</span>
											</li>
										</template>
									</ul>
								</div>
							</div>
							<div class="row mb-3 align-items-center">
								<label for="data_emprestimo" class="col-form-label col-sm-2">Data do empréstimo</label>
								<div class="col-sm-10">
									<Field type="date" class="form-control" name="data_emprestimo" v-model="form.data_emprestimo"
										:disabled="this.form.id" />
								</div>
							</div>
							<div class="row mb-3 align-items-center" v-if="this.form.id">
								<label for="data_devolucao" class="col-form-label col-sm-2">Data da devolucão</label>
								<div class="col-sm-10">
									<Field type="date" class="form-control" name="data_devolucao" v-model="form.data_devolucao" />
								</div>
							</div>
							<Field type="hidden" name="pessoa_id" v-model="form.pessoa_id"></Field>
							<Field type="hidden" name="livro_id" v-model="form.livro_id"></Field>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary" form="editForm">Salvar</button>
					</div>
				</VeeForm>
			</div>
		</div>
	</div>
</template>

<script>
import { Field, Form as VeeForm, ErrorMessage } from 'vee-validate';
import * as yup from 'yup'
import emprestimosService from '../../services/emprestimos.service';
import Loading from '../components/Loading.vue';
import { loadingMixin } from '../mixins/loadingMixin';
import Swal from 'sweetalert2'
import dayjs from 'dayjs'
import pessoasService from '../../services/pessoas.service';
import livrosService from '../../services/livros.service';

export default {
	name: 'EmprestimosForm',
	components: {
		Field,
		VeeForm,
		ErrorMessage,
		Loading
	},
	mixins: [loadingMixin],
	data: () => ({
		modalReady: false,
		modalInstance: null,
		dropdownPessoaInstance: null,
		dropdownLivroInstance: null,
		modalTitle: '',
		form: {
			id: '',
			pessoa_id: '',
			livro_id: '',
			data_emprestimo: ''
		},
		schema: yup.object({
			pessoa_id: yup.string().required('Pessoa é obrigatório').label('Pessoa'),
			livro_id: yup.string().required('Livro é obrigatório').label('Livro'),
		}),
		searchPessoaObj: {
			value: '',
			loading: false,
			results: [],
			selected: false
		},
		searchLivroObj: {
			value: '',
			loading: false,
			results: [],
			selected: false
		}
	}),
	mounted() {
		this.initializeBsComponents()
		this.listenEvents()
	},
	methods: {
		clearData() {
			console.log('clearData')
			this.form = {
				id: '',
				pessoa_id: '',
				livro_id: '',
				data_emprestimo: dayjs().format('YYYY-MM-DD'),
				data_devolucao: ''
			}
		},
		initializeBsComponents() {
			this.initializeBsModal()
			this.initializeBsDropdowns()
		},
		initializeBsModal() {
			const modal = document.getElementById('editEmprestimos')
			this.modalInstance = new bootstrap.Modal(modal, {
				keyboard: false,
				backdrop: true
			})

			modal.addEventListener('shown.bs.modal', () => {
				if (!this.form.id) {
					this.$refs.formEl.reset()
					this.$nextTick(() => {
						this.clearData()
						this.clearPessoa()
						this.clearLivro()
					})
				}
				this.modalReady = true
			})

			modal.addEventListener('hidden.bs.modal', () => {
				this.$emitter.emit('emprestimos.form.close')
				this.clearPessoa()
				this.clearLivro()
				this.modalReady = false
			})
		},
		initializeBsDropdowns() {
			const dropdownPessoa = this.$refs.dropdownPessoa;

			this.dropdownPessoaInstance = new bootstrap.Dropdown(dropdownPessoa, {
				autoClose: true,
				reference: this.$refs.inputPessoa
			});

			const dropdownLivro = this.$refs.dropdownLivro;
			this.dropdownLivroInstance = new bootstrap.Dropdown(dropdownLivro, {
				autoClose: true,
				reference: this.$refs.inputLivro
			});
		},
		listenEvents() {
			this.$emitter.on('emprestimos.create', this.novo)
			this.$emitter.on('emprestimos.edit', this.editar)
		},
		novo() {
			this.clearData()
			this.showModal()
		},
		async editar(id) {
			this.clearData();
			const { data } = await emprestimosService.getEmprestimo(id)
			this.form.id = data.id
			this.form.pessoa_id = data.pessoa.id
			this.form.livro_id = data.livro.id
			this.form.data_emprestimo = data.data_emprestimo
			this.form.data_devolucao = data.data_devolucao
			this.selectPessoa(data.pessoa)
			this.selectLivro(data.livro)
			this.showModal()
		},
		showModal() {
			this.updateModalTitle()
			this.modalInstance.show()
		},
		hideModal() {
			this.modalInstance.hide()
		},
		async onSubmit(values, { resetForm }) {
			Swal.fire({
				title: 'Salvando...',
				didOpen: () => {
					Swal.showLoading()
				}
			})
			try {
				if (this.form.id) {
					await emprestimosService.updateEmprestimo(this.form.id, values)
				} else {
					await emprestimosService.createEmprestimo(values)
				}
				Swal.fire({
					icon: 'success',
					title: 'Successo',
					text: 'Emprestimo salvo com sucesso!',
					timer: 2000,
					timerProgressBar: true,
				}).then(() => {
					this.clearData()
					resetForm()
					this.hideModal()
					this.$emitter.emit('emprestimos.reload')
				})
			} catch (err) {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Algo deu errado!',
				}).then(() => {
					this.showModal()
				})
			}
		},
		updateModalTitle() {
			this.modalTitle = this.form.id ? `Editar: ${this.searchLivroObj.value}` : 'Novo Empréstimo'
		},
		async searchPessoas() {
			const { data } = await pessoasService.getPessoas({
				q: this.searchPessoaObj.value,
				searchField: 'nome'
			})
			this.searchPessoaObj.results = data.data
			if (this.searchPessoaObj.results.length === 0) {
				this.searchPessoaObj.results.push({
					disabled: true,
					nome: 'Nenhum resultado encontrado'
				})
			}
			this.dropdownPessoaInstance.show()
		},
		selectPessoa(pessoa) {
			this.form.pessoa_id = pessoa.id
			this.searchPessoaObj.value = pessoa.nome
			this.searchPessoaObj.selected = true
			this.dropdownPessoaInstance.hide()
		},
		clearPessoa() {
			this.form.pessoa_id = ''
			this.searchPessoaObj.value = ''
			this.searchPessoaObj.selected = false
		},
		onEnterPessoa() {
			if (this.searchPessoaObj.value && !this.searchPessoaObj.selected) {
				this.dropdownPessoaInstance.show()
			}
		},
		async onBlurPessoa() {
			setTimeout(() => {
				if (!this.searchPessoaObj.selected) {
					this.$refs.veeForm.setFieldError('pessoa', 'Pessoa é obrigatório')
				} else {
					this.$refs.veeForm.setFieldError('pessoa', null)
				}
				this.dropdownPessoaInstance.hide()
			}, 100)
		},
		async searchLivros() {
			const { data } = await livrosService.getLivros({
				q: this.searchLivroObj.value,
				searchField: 'titulo'
			})
			this.searchLivroObj.results = data.data.map(k => {
				k.titulo = `${k.titulo} - ${k.autores}`
				if (k.quantidade_disponivel === 0) {
					k.titulo = `${k.titulo} - (Indisponível)`
					k.disabled = true
				} else {
					k.titulo = `${k.titulo} - (Disponível: ${k.quantidade_disponivel})`
				}
				return k
			})
			this.dropdownLivroInstance.show()
		},
		selectLivro(livro) {
			this.form.livro_id = livro.id
			this.searchLivroObj.value = livro.titulo
			this.searchLivroObj.selected = true
			this.dropdownLivroInstance.hide()
		},
		clearLivro() {
			this.form.livro_id = ''
			this.searchLivroObj.value = ''
			this.searchLivroObj.selected = false
		},
		onBlurLivro() {
			setTimeout(() => {
				if (!this.searchLivroObj.selected) {
					this.$refs.veeForm.setFieldError('livro', 'Livro é obrigatório')
				} else {
					this.$refs.veeForm.setFieldError('livro', null)
				}
				this.dropdownLivroInstance.hide()
			}, 100);
		},
		onEnterLivro() {
			if (this.searchLivroObj.value && !this.searchLivroObj.selected) {
				this.dropdownLivroInstance.show()
			}
		},
		onHandlePessoaEnter() {
			event.stopPropagation()
			event.preventDefault()
			console.log('onHandlePessoaEnter')
		}
	}
}
</script>
