<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Apakah Anda yakin ingin logout dari website?</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Upload -->
<div class="modal fade" id="confirmUploadModal" tabindex="-1" role="dialog" aria-labelledby="confirmUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmUploadModalLabel">Konfirmasi Upload File</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>File yang Anda pilih cukup besar (<strong id="fileSizeDisplay"></strong> MB).</p>
                <p>Mengunggah file besar akan memakan kapasitas penyimpanan server. Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="button" id="btnConfirmUpload">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let formToSubmit = null;

            $(document).on('submit', '.theme-form', function(e) {
                const fileInput = $(this).find('input[name="file"]')[0];
                
                if (fileInput && fileInput.files.length > 0) {
                    const fileSize = fileInput.files[0].size;
                    const tenMB = 10 * 1024 * 1024; // 10MB in bytes

                    if (fileSize > tenMB) {
                        // Cek apakah sudah dikonfirmasi
                        if ($(this).data('confirmed')) {
                            return true;
                        }

                        e.preventDefault();
                        formToSubmit = this; // Simpan form yang akan di-submit
                        const sizeInMB = (fileSize / (1024 * 1024)).toFixed(2);
                        $('#fileSizeDisplay').text(sizeInMB);
                        
                        // Tampilkan modal
                        $('#confirmUploadModal').modal('show');
                        return false;
                    }
                }
            });

            // Handler tombol konfirmasi
            $('#btnConfirmUpload').on('click', function() {
                if (formToSubmit) {
                    $(formToSubmit).data('confirmed', true);
                    formToSubmit.submit();
                }
            });
        });
    </script>
@endpush
