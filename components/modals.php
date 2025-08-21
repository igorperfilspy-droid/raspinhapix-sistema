<div id="backdrop2" class="modal-backdrop"></div>

<!-- Deposit Modal -->
<section id="depositModal" class="modal-container">
    <div class="modal-wrapper">
        <div class="modal-card">
            <button id="closeDepositModal" class="modal-close">
                <i class="bi bi-x"></i>
            </button>

            <div class="modal-icon">
                <i class="bi bi-credit-card"></i>
            </div>

            <h2 class="modal-title">Depósito</h2>

            <form id="depositForm" class="modal-form">
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <input type="text" name="amount" id="amountInput" required
                           class="form-input"
                           placeholder="Digite o valor do depósito" inputmode="numeric">
                </div>

                <div class="quick-amounts">
                    <button type="button" data-value="20" class="quick-amount">R$ 20</button>
                    <button type="button" data-value="50" class="quick-amount">R$ 50</button>
                    <button type="button" data-value="100" class="quick-amount">R$ 100</button>
                    <button type="button" data-value="200" class="quick-amount">R$ 200</button>
                </div>

                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <input type="text" name="cpf" id="cpfInput" required
                           class="form-input"
                           placeholder="CPF (000.000.000-00)" maxlength="14">
                </div>

                <button type="submit" class="submit-btn">
                    <i class="bi bi-check-circle"></i>
                    Depositar
                </button>
            </form>

            <div id="qrArea" class="qr-area">
