<template>
	<div class="modal fade" id="editPessoas">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<VeeForm v-slot="{ handleSubmit, errors }" :validation-schema="schema" as="div" class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">{{ modalTitle }}</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form @submit="handleSubmit($event, onSubmit)" id="pessoasForm" ref="form">
						<div class="row mb-3">
							<label for="id" class="col-form-label col-sm-2">ID</label>
							<div class="col-sm-10">
								<Field type="text" class="form-control" name="id" disabled v-model="form.id" :standalone="true" />
							</div>
						</div>
						<div class="row mb-3" v-if="tipoForm === 'A'">
							<label for="matricula" class="col-form-label col-sm-2">Matricula</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="matricula" v-model="form.matricula">
									<input type="text" id="matricula" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="matricula" as="div" class="invalid-feedback" />
								</Field>

							</div>
						</div>
						<div class="row mb-3" v-if="tipoForm === 'P'">
							<label for="titulacao" class="col-form-label col-sm-2">Titulação</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="titulacao" v-model="form.titulacao">
									<input type="text" id="titulacao" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="titulacao" as="div" class="invalid-feedback" />
								</Field>

							</div>
						</div>
						<div class="row mb-3">
							<label for="nome" class="col-form-label col-sm-2">Nome</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="nome" v-model="form.nome">
									<input type="text" id="nome" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="nome" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="cpf" class="col-form-label col-sm-2">CPF</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="cpf" v-model="form.cpf">
									<input type="tel" id="cpf" class="form-control" :class="{ 'is-invalid': errorMessage && modalReady }"
										v-bind="field" v-mask="'###.###.###-##'" />
									<ErrorMessage v-if="errorMessage" name="cpf" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="rg" class="col-form-label col-sm-2">RG</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="rg" v-model="form.rg">
									<input type="number" id="rg" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="rg" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="telefone" class="col-form-label col-sm-2">Telefone</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="telefone" v-model="form.telefone">
									<input type="tel" id="telefone" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field"
										v-mask="['(##) #####-####', '(##) ####-####']" />
									<ErrorMessage v-if="errorMessage" name="telefone" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="celular" class="col-form-label col-sm-2">Celular</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="celular" v-model="form.celular">
									<input type="tel" id="celular" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" v-mask="['(##) #####-####']" />
									<ErrorMessage v-if="errorMessage" name="celular" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="email" class="col-form-label col-sm-2">Email</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="email" v-model="form.email">
									<input type="email" id="email" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="email" as="div" class="invalid-feedback" />
								</Field>
							</div>
						</div>
						<div class="row mb-3">
							<label for="endereco" class="col-form-label col-sm-2">Endereço</label>
							<div class="col-sm-10">
								<Field v-slot="{ errorMessage, field, meta }" name="endereco" v-model="form.endereco">
									<input type="text" id="endereco" class="form-control"
										:class="{ 'is-invalid': errorMessage && modalReady }" v-bind="field" />
									<ErrorMessage v-if="errorMessage" name="endereco" as="div" class="invalid-feedback" />
								</Field>

							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary" form="pessoasForm">Salvar</button>
				</div>
			</VeeForm>
		</div>
	</div>
</template>

<script>
import { Field, Form as VeeForm, ErrorMessage } from 'vee-validate';
import * as yup from 'yup'
import Loading from '../components/Loading.vue';
import { loadingMixin } from '../mixins/loadingMixin';
import Swal from 'sweetalert2'
import pessoasService from '../../services/pessoas.service';

export default {
	name: 'EditPessoas',
	props: {
		tipoForm: {
			type: String,
			required: true
		},
	},
	components: {
		Field,
		VeeForm,
		ErrorMessage,
		Loading
	},
	mixins: [loadingMixin],
	data: function () {
		return {
			modalReady: false,
			modalInstance: null,
			modalTitle: '',
			form: {
				id: null,
				nome: '',
				cpf: '',
				rg: '',
				telefone: '',
				celular: '',
				email: '',
				endereco: '',
				matricula: '',
				titulacao: ''
			}
		}
	},
	computed: {
		tipoFormLabel() {
			return this.tipoForm === 'A' ? 'Aluno' : 'Professor';
		},
		schema() {
			let schema = yup.object({
				nome: yup.string().required('Nome é obrigatório'),
				cpf: yup.string().required('CPF é obrigatório'),
				rg: yup.string().required('RG é obrigatório'),
				telefone: yup.string().required('Telefone é obrigatório'),
				celular: yup.string().required('Celular é obrigatório'),
				email: yup.string().required('Email é obrigatório'),
				endereco: yup.string().required('Endereço é obrigatório'),
			})

			if (this.tipoForm === 'A') {
				schema = schema.concat(yup.object().shape({
					matricula: yup.string().required('Matrícula é obrigatório'),
				}))
			} else if (this.tipoForm === 'P') {
				schema = schema.concat(yup.object().shape({
					titulacao: yup.string().required('Titulação é obrigatório'),
				}))
			}

			return schema
		}
	},
	mounted() {
		const modal = document.getElementById('editPessoas')
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
			this.$emitter.emit('pessoas.form.close')
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
			this.$emitter.on('pessoas.create', this.novo)
			this.$emitter.on('pessoas.edit', this.editar)
		},
		novo() {
			this.clearData()
			this.showModal()
		},
		async editar(id) {
			this.clearData();
			const { data } = await pessoasService.getPessoa(id)
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
					await pessoasService.updatePessoa(this.form.id,
						{
							...values,
							tipo: this.tipoForm
						})
				} else {
					await pessoasService.createPessoa({
						...values,
						tipo: this.tipoForm
					})
				}
				Swal.fire({
					icon: 'success',
					title: 'Successo',
					text: `${this.tipoFormLabel} salvo com sucesso!`,
					timer: 2000,
					timerProgressBar: true,
				}).then(() => {
					this.clearData()
					resetForm()
					this.hideModal()
					this.$emitter.emit('pessoas.reload')
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
			this.modalTitle = this.form.id ? `Editar: ${this.form.nome}` : `Novo ${this.tipoFormLabel}`
		}
	}
}
</script>
