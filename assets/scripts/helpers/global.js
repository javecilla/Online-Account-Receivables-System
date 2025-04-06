const openModal = (modalId) => {
	$(modalId).attr('data-backdrop', 'static').modal('show')
}

const closeModal = (modalId) => {
	$(modalId).modal('hide')
}
