<div id="backdrop2"<?php class="modal-backdrop"></div>

<!--<?php Deposit Modal -->
<section id="depositModal"<?php class="modal-container">
<?php <div class="modal-wrapper">
<?php <div class="modal-card">
<?php <button id="closeDepositModal"<?php class="modal-close">
<?php <i class="bi bi-x"></i>
<?php </button>

<?php <div class="modal-icon">
<?php <i class="bi bi-credit-card"></i>
<?php </div>

<?php <h2 class="modal-title">Depósito</h2>

<?php <form id="depositForm"<?php class="modal-form">
<?php <div class="form-group">
<?php <div class="input-icon">
<?php <i class="bi bi-cash-stack"></i>
<?php </div>
<?php <input type="text"<?php name="amount"<?php id="amountInput"<?php required class="form-input"
<?php placeholder="Digite o valor do depósito"<?php inputmode="numeric">
<?php </div>

<?php <div class="quick-amounts">
<?php <button type="button"<?php data-value="20"<?php class="quick-amount">R$<?php 20</button>
<?php <button type="button"<?php data-value="50"<?php class="quick-amount">R$<?php 50</button>
<?php <button type="button"<?php data-value="100"<?php class="quick-amount">R$<?php 100</button>
<?php <button type="button"<?php data-value="200"<?php class="quick-amount">R$<?php 200</button>
<?php </div>

<?php <div class="form-group">
<?php <div class="input-icon">
<?php <i class="bi bi-person-vcard"></i>
<?php </div>
<?php <input type="text"<?php name="cpf"<?php id="cpfInput"<?php required class="form-input"
<?php placeholder="CPF (000.000.000-00)"<?php maxlength="14">
<?php </div>

<?php <button type="submit"<?php class="submit-btn">
<?php <i class="bi bi-check-circle"></i>
<?php Depositar </button>
<?php </form>

<?php <div id="qrArea"<?php class="qr-area">
