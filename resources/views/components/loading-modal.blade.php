<div class="modal fade"
     id="loadingModal"
     tabindex="-1"
     aria-hidden="true"
     data-bs-backdrop="static"
     data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">

              <!-- Logo + pulse rings -->
              <div class="position-relative d-inline-flex align-items-center justify-content-center mb-4" style="width:90px;height:90px;">
                <span class="pulse-ring"></span>
                <span class="pulse-ring pulse-ring--2"></span>
                <img src="{{ asset('logo.png') }}" class="logo-beat rounded-circle" style="width:90px;height:90px;position:relative;z-index:2;">
              </div>

              <!-- ECG line -->
              <div class="ecg-wrap mx-auto mb-4">
                <div class="ecg-fade-left"></div>
                <div class="ecg-fade-right"></div>
                <svg class="ecg-svg" viewBox="0 0 360 36">
                  <polyline class="ecg-line" points="0,18 20,18 28,18 36,4 44,32 52,2 58,18 70,18 80,18 88,18 96,18 104,4 112,32 120,2 126,18 138,18 148,18 156,18 164,4 172,32 180,2 186,18 198,18"/>
                  <polyline class="ecg-line" points="180,18 200,18 208,18 216,4 224,32 232,2 238,18 250,18 260,18 268,18 276,18 284,4 292,32 300,2 306,18 318,18 328,18 336,18 344,4 352,32 360,2"/>
                </svg>
              </div>

              <h5 class="mb-2">Processing</h5>
              <p class="text-muted mb-0">Please wait while we process your request…</p>
            </div>
        </div>
    </div>
</div>