<template>
	<div>
		<div class="modal fade" id="editLivros">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<VeeForm v-slot="{ handleSubmit }" :validation-schema="schema" as="div" class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">{{ modalTitle }}</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form @submit="handleSubmit($event, onSubmit)" id="editForm" ref="form">
							<div class="row mb-3">
								<label for="id" class="col-form-label col-sm-2">ID</label>
								<div class="col-sm-10">
									<Field type="text" class="form-control" name="id" disabled v-model="form.id" :standalone="true" />
								</div>
							</div>
							<div class="row mb-3">
								<label for="titulo" class="col-form-label col-sm-2">Título</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="titulo" v-model="form.titulo">
										<input type="text" id="titulo" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="titulo" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="ano" class="col-form-label col-sm-2">Ano</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="ano" v-model="form.ano">
										<input type="number" id="ano" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="ano" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="edicao" class="col-form-label col-sm-2">Edição</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="edicao" v-model="form.edicao">
										<input type="number" id="edicao" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="edicao" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="editora" class="col-form-label col-sm-2">Editora</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="editora" v-model="form.editora">
										<input type="text" id="editora" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="editora" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="autores" class="col-form-label col-sm-2">Autores</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="autores" v-model="form.autores">
										<input type="text" id="autores" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="autores" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="assunto" class="col-form-label col-sm-2">Assunto</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="assunto" v-model="form.assunto">
										<input type="text" id="assunto" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="assunto" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
							<div class="row mb-3">
								<label for="quantidade" class="col-form-label col-sm-2">Quantidade</label>
								<div class="col-sm-10">
									<Field v-slot="{ errorMessage, field, meta }" name="quantidade" v-model="form.quantidade">
										<input type="number" id="quantidade" class="form-control"
											:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
										<ErrorMessage v-if="errorMessage" name="quantidade" as="div" class="invalid-feedback" />
									</Field>
								</div>
							</div>
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
import livrosService from '../../services/livros.service';
import Loading from '../components/Loading.vue';
import { loadingMixin } from '../mixins/loadingMixin';
import Swal from 'sweetalert2'

export default {
	name: 'EditLivros',
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
		modalTitle: '',
		form: {
			id: '',
			titulo: '',
			ano: '',
			edicao: '',
			editora: '',
			autores: '',
			assunto: '',
			quantidade: ''
		},
		schema: yup.object({
			titulo: yup.string().required('Campo obrigatório').label('Título'),
			ano: yup.number().typeError('Campo obrigatório').required('Campo obrigatório').label('Ano'),
			edicao: yup.number().typeError('Campo obrigatório').required('Campo obrigatório').label('Edição'),
			editora: yup.string().required('Campo obrigatório').label('Editora'),
			autores: yup.string().required('Campo obrigatório').label('Autores'),
			assunto: yup.string().required('Campo obrigatório').label('Assunto'),
			quantidade: yup.number().typeError('Campo obrigatório').required('Campo obrigatório').label('Quantidade')
		})
	}),
	mounted() {
		const modal = document.getElementById('editLivros')
		this.modalInstance = new bootstrap.Modal(modal, {
			keyboard: false,
			backdrop: true
		})

		modal.addEventListener('shown.bs.modal', () => {
			if (!this.form.id) {
				this.clearData()
				this.$refs.form.reset()
			}
			this.modalReady = true
		})

		modal.addEventListener('hidden.bs.modal', () => {
			this.$emitter.emit('livros.form.close')
			this.modalReady = false
		})

		this.listenEvents()
	},
	methods: {
		clearData() {
			this.form = {
				id: '',
				titulo: '',
				ano: '',
				edicao: '',
				editora: '',
				autores: '',
				assunto: '',
				quantidade: ''
			}
		},
		listenEvents() {
			this.$emitter.on('livros.create', this.novo)
			this.$emitter.on('livros.edit', this.editar)
		},
		novo() {
			this.clearData()
			this.showModal()
		},
		async editar(id) {
			this.clearData();
			const { data } = await livrosService.getLivro(id)
			this.form = data
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
					await livrosService.updateLivro(this.form.id, values)
				} else {
					await livrosService.createLivro(values)
				}
				Swal.fire({
					icon: 'success',
					title: 'Successo',
					text: 'Livro salvo com sucesso!',
					timer: 2000,
					timerProgressBar: true,
				}).then(() => {
					this.clearData()
					resetForm()
					this.hideModal()
					this.$emitter.emit('livros.reload')
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
			this.modalTitle = this.form.id ? `Editar: ${this.form.titulo}` : 'Novo Livro'
		}
	}
}
</script>
