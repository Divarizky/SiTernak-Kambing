document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('kambingModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('formKambing');
    const btnSimpan = document.getElementById('btnSimpan');
    const actionInput = document.getElementById('action');
    const idKambingInput = document.getElementById('id_kambing');

    const btnAdd = document.querySelector('.btn-add-kambing');
    const btnClose = document.querySelector('.close-button');
    const btnBatal = document.querySelector('.btn-batal');

    // Fungsi untuk membuka modal (form_kambing.php)
    function openModal() {
        modal.style.display = 'block';
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        modal.style.display = 'none';
        form.reset();
    }

    // Fungsi untuk mengisi form, memastikan field bisa diedit/dilihat sesuai kondisi
    function populateForm(kambing, isReadOnly = false) {
        // Fungsi reset semua field ke kondisi bisa diisi
        Array.from(form.elements).forEach(el => {
            el.readOnly = false;
            if (el.tagName === 'SELECT') {
                el.disabled = false;
            }
        });

        // Fungsi untuk isi semua field form berdasarkan data
        for (const key in kambing) {
            const field = form.elements[key];
            if (field) {
                field.value = kambing[key];
                field.readOnly = isReadOnly;
                if (field.tagName === 'SELECT') {
                    field.disabled = isReadOnly;
                }
            }
        }
        idKambingInput.value = kambing.id_kambing || '';
    }

    // Tombol "Tambah Data Kambing"
    btnAdd.addEventListener('click', () => {
        if (!isLoggedIn) {
            window.location.href = "auth/login.php";
            return;
        }
        
        form.reset();
        modalTitle.textContent = 'Tambah Data Kambing';
        actionInput.value = 'create';
        btnSimpan.style.display = 'block';
        populateForm({}, false); // Kosongkan dan buat form bisa diisi
        openModal();
    });

    // Tombol "Lihat"
    document.querySelectorAll('.btn-lihat').forEach(button => {
        button.addEventListener('click', () => {
            const kambingData = JSON.parse(button.getAttribute('data-kambing'));
            modalTitle.textContent = 'Detail Data Kambing';
            btnSimpan.style.display = 'none'; // Sembunyikan tombol simpan
            populateForm(kambingData, true); // Isi form dan buat read-only
            openModal();
        });
    });

    // Tombol "Edit"
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', () => {
            if (!isLoggedIn) {
                window.location.href = "auth/login.php";
                return;
            }
            const kambingData = JSON.parse(button.getAttribute('data-kambing'));
            modalTitle.textContent = 'Edit Data Kambing';
            actionInput.value = 'update';
            btnSimpan.style.display = 'block';
            populateForm(kambingData, false);
            openModal();
        });
    });


    // Handle form submission dengan AJAX
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Mencegah reload halaman

        const formData = new FormData(form);

        fetch('../config/database.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Menampilkan pesan sukses
                    closeModal();
                    location.reload(); // Memuat ulang halaman untuk melihat perubahan
                } else {
                    alert('Error: ' + data.message); // pesan error
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan teknis saat mengirim data.');
            });
    });

    // Handle hapus dengan AJAX
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', () => {
            if (!isLoggedIn) {
                window.location.href = "auth/login.php";
                return;
            }

            const kambingData = JSON.parse(button.getAttribute('data-kambing'));
            const confirmation = confirm(`Apakah Anda yakin ingin menghapus data kambing dengan ID: KB${String(kambingData.id_kambing).padStart(3, '0')}?`);

            if (confirmation) {
                const formData = new FormData();
                formData.append('id_kambing', kambingData.id_kambing);
                formData.append('action', 'delete');

                fetch('../config/database.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan teknis saat menghapus data.');
                    });
            }
        });
    });


    // Tombol close dan batal di modal
    btnClose.addEventListener('click', closeModal);
    btnBatal.addEventListener('click', closeModal);

    // Klik di luar modal untuk menutup
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            closeModal();
        }
    });
});
