<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cash Payment Voucher - CPV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
    }
    .cpv-card {
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .form-label {
      font-weight: 500;
    }
    .btn-submit {
      border-radius: 10px;
      padding: 8px 30px;
    }
    .form-control, .form-select {
      height: 45px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="p-4 rounded shadow-sm bg-white cpv-card">
    <h4 class="mb-4 fw-bold text-primary">🧾 Cash Payment Voucher (CPV)</h4>

    <form id="cpvForm" enctype="multipart/form-data" autocomplete="off">
      <!-- Voucher & Date -->
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label">Voucher No.</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="voucher_no" placeholder="e.g. CPV-001" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-sm border-0 border-bottom" name="date" required>
        </div>
      </div>

      <!-- Expense Type & Paid To -->
      <div class="row g-4 mt-3">
        <div class="col-md-6">
          <label class="form-label">Expense Type</label>
          <select class="form-select form-select-sm border-0 border-bottom" name="expense_type" required>
            <option selected disabled>Choose Expense Type</option>
            <option value="vendor">Vendor CPV</option>
            <option value="employee">Employee CPV</option>
            <option value="general">General CPV</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Paid To</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="paid_to" placeholder="Company / Person Name" required>
        </div>
      </div>

      <!-- Payment Mode -->
      <div class="row g-4 mt-3" id="paymentContainer">
        <div class="col-md-6">
          <label class="form-label">Payment Mode</label>
          <select id="paymentMode" class="form-select form-select-sm border-0 border-bottom" name="payment_mode[]">
            <option disabled selected>Choose...</option>
            <option value="cash">Cash</option>
            <option value="cheque">Cheque</option>
            <option value="bank">Bank Transfer</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Amount (in Numbers)</label>
          <input type="number" class="form-control form-control-sm border-0 border-bottom amount-field" name="amount[]" placeholder="10000" oninput="updateAmountReceived()">
        </div>
      </div>

      <!-- Conditional Cheque Fields -->
      <div id="chequeFields" class="row g-4 mt-3 d-none">
        <div class="col-md-4">
          <label class="form-label">Cheque No.</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="cheque_no[]" placeholder="e.g. 123456">
        </div>
        <div class="col-md-4">
          <label class="form-label">Cheque Date</label>
          <input type="date" class="form-control form-control-sm border-0 border-bottom" name="cheque_date[]">
        </div>
        <div class="col-md-4">
          <label class="form-label">Bank Name</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="cheque_bank_name[]" placeholder="e.g. HBL">
        </div>
      </div>

      <!-- Conditional Bank Fields -->
      <div id="bankFields" class="row g-4 mt-3 d-none">
        <div class="col-md-4">
          <label class="form-label">Bank Name</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="bank_name[]" placeholder="e.g. Meezan">
        </div>
        <div class="col-md-4">
          <label class="form-label">Account No</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="account_no[]" placeholder="e.g. 0123456789">
        </div>
        <div class="col-md-4">
          <label class="form-label">Transaction ID</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="transaction_id[]" placeholder="e.g. TXN12345">
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-sm btn-secondary" type="button" onclick="addPaymentBlock()">➕ Add Payment Method</button>
      </div>

      <!-- Total, Received, Pending, Remaining -->
      <div class="row mt-3">
        <div class="col-md-6">
          <label class="form-label">Total Amount</label>
          <input type="number" class="form-control form-control-sm border-0 border-bottom" id="totalAmount" name="total_amount" placeholder="Total Amount" value="0" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Amount Received</label>
          <input type="number" class="form-control form-control-sm border-0 border-bottom" id="amountReceived" name="amount_received" placeholder="Amount Received" value="0" readonly>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6">
          <label class="form-label">Pending Amount</label>
          <input type="number" class="form-control form-control-sm border-0 border-bottom" name="pending_amount" placeholder="Pending Amount" value="0" >
        </div>
        <div class="col-md-6">
          <label class="form-label">Remaining Amount</label>
          <input type="number" class="form-control form-control-sm border-0 border-bottom" id="remainingAmount" name="remaining_amount" placeholder="Remaining Amount" readonly>
        </div>
      </div>

      <!-- Amount in Words -->
      <div class="row g-4 mt-3">
        <div class="col-md-12">
          <label class="form-label">Amount (in Words)</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="amount_in_words" placeholder="Ten Thousand Rupees Only">
        </div>
      </div>

      <!-- File Upload -->
      <div class="row g-4 mt-3">
        <div class="col-md-12">
          <label class="form-label">Upload File (optional)</label>
          <input type="file" class="form-control form-control-sm border-0 border-bottom" name="uploaded_file">
        </div>
      </div>

      <!-- Description -->
      <div class="mt-4">
        <label class="form-label">Description</label>
        <textarea class="form-control border-0 border-bottom" rows="2" name="description" placeholder="Purpose of payment..."></textarea>
      </div>

      <!-- Signatures -->
      <div class="row g-4 mt-4">
        <div class="col-md-4">
          <label class="form-label">Prepared By</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="prepared_by">
        </div>
        <div class="col-md-4">
          <label class="form-label">Approved By</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="approved_by">
        </div>
        <div class="col-md-4">
          <label class="form-label">Received By</label>
          <input type="text" class="form-control form-control-sm border-0 border-bottom" name="received_by">
        </div>
      </div>

      <!-- Submit -->
      <div class="text-end mt-4">
        <button class="btn btn-outline-primary rounded-pill px-4" type="submit">✅ Submit Voucher</button>
      </div>
    </form>
    <div id="successAlert" class="alert alert-success mt-3 d-none" role="alert">
      Form submitted successfully!
    </div>

<!-- Submitted Vouchers List -->
<div class="mt-5">
  <h5 class="fw-bold">Submitted Vouchers</h5>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle" id="voucherListTable">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Voucher No.</th>
          <th>Date</th>
          <th>Expense Type</th>
          <th>Paid To</th>
          <th>Total Amount</th>
          <th>Amount Received</th>
          <th>Pending Amount</th>
          <th>Remaining Amount</th>
          <th>Amount in Words</th>
          <th>Payment Methods</th>
          <th>File</th>
          <th>Description</th>
          <th>Prepared By</th>
          <th>Approved By</th>
          <th>Received By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Entries will be added here -->
      </tbody>
    </table>
  </div>
  <div class="text-end mt-3">
    <button class="btn btn-success" id="finalSubmitBtn">Final Submit All</button>
  </div>
</div>

<script>
  let paymentIndex = 0;

  function addPaymentBlock() {
    const container = document.getElementById('paymentContainer');
    const index = paymentIndex++;

    const html = `
      <div class="payment-block border p-3 mb-3 rounded" data-index="${index}">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Payment Mode</label>
            <select class="form-select form-select-sm border-0 border-bottom" name="payment_mode[]" onchange="togglePaymentFields(this, ${index})">
              <option disabled selected>Select Mode</option>
              <option value="cash">Cash</option>
              <option value="cheque">Cheque</option>
              <option value="bank">Bank Transfer</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Amount</label>
            <input type="number" class="form-control form-control-sm border-0 border-bottom amount-field" name="amount[]" placeholder="10000" oninput="updateAmountReceived()">
          </div>
          <div class="col-md-4 text-end">
            <button type="button" class="btn btn-sm btn-danger" onclick="removePaymentBlock(${index})">🗑️ Remove</button>
          </div>
        </div>

        <div class="row g-3 mt-2 d-none" id="chequeFields-${index}">
          <div class="col-md-4">
            <label class="form-label">Cheque No.</label>
            <input type="text" class="form-control form-control-sm border-0 border-bottom" name="cheque_no[]">
          </div>
          <div class="col-md-4">
            <label class="form-label">Cheque Date</label>
            <input type="date" class="form-control form-control-sm border-0 border-bottom" name="cheque_date[]">
          </div>
          <div class="col-md-4">
            <label class="form-label">Bank Name</label>
            <input type="text" class="form-control form-control-sm border-0 border-bottom" name="cheque_bank_name[]">
          </div>
        </div>

        <div class="row g-3 mt-2 d-none" id="bankFields-${index}">
          <div class="col-md-4">
            <label class="form-label">Bank Name</label>
            <input type="text" class="form-control form-control-sm border-0 border-bottom" name="bank_name[]">
          </div>
          <div class="col-md-4">
            <label class="form-label">Account No.</label>
            <input type="text" class="form-control form-control-sm border-0 border-bottom" name="account_no[]">
          </div>
          <div class="col-md-4">
            <label class="form-label">Transaction ID</label>
            <input type="text" class="form-control form-control-sm border-0 border-bottom" name="transaction_id[]">
          </div>
        </div>
      </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
  }

  function togglePaymentFields(selectElem, index) {
    const cheque = document.getElementById(`chequeFields-${index}`);
    const bank = document.getElementById(`bankFields-${index}`);

    cheque.classList.add('d-none');
    bank.classList.add('d-none');

    if (selectElem.value === 'cheque') {
      cheque.classList.remove('d-none');
    } else if (selectElem.value === 'bank') {
      bank.classList.remove('d-none');
    }
  }

  function removePaymentBlock(index) {
    const block = document.querySelector(`.payment-block[data-index="${index}"]`);
    block.remove();
  }

//   // Add first block on load
//   window.onload = () => addPaymentBlock();
</script>

<script>
  const paymentMode = document.getElementById('paymentMode');
  const chequeFields = document.getElementById('chequeFields');
  const bankFields = document.getElementById('bankFields');

  paymentMode.addEventListener('change', function () {
    chequeFields.classList.add('d-none');
    bankFields.classList.add('d-none');

    if (this.value === 'cheque') {
      chequeFields.classList.remove('d-none');
    } else if (this.value === 'bank') {
      bankFields.classList.remove('d-none');
    }
  });
</script>
<script>
  // Auto calculate Remaining Amount
  document.getElementById('totalAmount').addEventListener('input', calculateRemaining);
  document.getElementById('amountReceived').addEventListener('input', calculateRemaining);

  function calculateRemaining() {
    const total = parseFloat(document.getElementById('totalAmount').value) || 0;
    const received = parseFloat(document.getElementById('amountReceived').value) || 0;
    const remaining = total - received;
    document.getElementById('remainingAmount').value = remaining;
  }
</script>

<script>
  function updateAmountReceived() {
    let totalReceived = 0;
    document.querySelectorAll('.amount-field').forEach(input => {
      const val = parseFloat(input.value) || 0;
      totalReceived += val;
    });
    document.getElementById('amountReceived').value = totalReceived;
    calculateRemaining(); // Recalculate Remaining Amount as well
  }


</script>

<script>
  // Store all vouchers in an array for easy editing/final submit
  let vouchersData = [];

  document.getElementById('cpvForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // --- Collect form data ---
    const form = this;
    const formData = new FormData(form);
    // Payment methods (arrays)
    const paymentModes = formData.getAll('payment_mode[]');
    const amounts = formData.getAll('amount[]');
    const chequeNos = formData.getAll('cheque_no[]');
    const chequeDates = formData.getAll('cheque_date[]');
    const chequeBankNames = formData.getAll('cheque_bank_name[]');
    const bankNames = formData.getAll('bank_name[]');
    const accountNos = formData.getAll('account_no[]');
    const transactionIds = formData.getAll('transaction_id[]');

    // Build payment summary (all details)
    let paymentSummary = '';
    let paymentDetailsArr = [];
    for (let i = 0; i < paymentModes.length; i++) {
      let mode = paymentModes[i] || '-';
      let amt = amounts[i] || '-';
      let payObj = { mode, amt };
      if (mode === 'cheque') {
        paymentSummary += `Cheque<br>Amount: ${amt}<br>No: ${chequeNos[i] || '-'}<br>Date: ${chequeDates[i] || '-'}<br>Bank: ${chequeBankNames[i] || '-'}<hr style='margin:2px 0;'>`;
        payObj.cheque_no = chequeNos[i] || '-';
        payObj.cheque_date = chequeDates[i] || '-';
        payObj.cheque_bank_name = chequeBankNames[i] || '-';
      } else if (mode === 'bank') {
        paymentSummary += `Bank Transfer<br>Amount: ${amt}<br>Bank: ${bankNames[i] || '-'}<br>Acc: ${accountNos[i] || '-'}<br>Txn: ${transactionIds[i] || '-'}<hr style='margin:2px 0;'>`;
        payObj.bank_name = bankNames[i] || '-';
        payObj.account_no = accountNos[i] || '-';
        payObj.transaction_id = transactionIds[i] || '-';
      } else if (mode === 'cash') {
        paymentSummary += `Cash<br>Amount: ${amt}<hr style='margin:2px 0;'>`;
      }
      paymentDetailsArr.push(payObj);
    }
    if (!paymentSummary) paymentSummary = '-';

    // File name
    let fileInput = form.querySelector('input[type="file"]');
    let fileName = fileInput && fileInput.files.length > 0 ? fileInput.files[0].name : '-';

    // Helper to get value or dash
    function valOrDash(key) {
      return formData.get(key) ? formData.get(key) : '-';
    }

    // Build voucher object
    const voucherObj = {
      voucher_no: valOrDash('voucher_no'),
      date: valOrDash('date'),
      expense_type: valOrDash('expense_type'),
      paid_to: valOrDash('paid_to'),
      total_amount: valOrDash('total_amount'),
      amount_received: valOrDash('amount_received'),
      pending_amount: valOrDash('pending_amount'),
      remaining_amount: valOrDash('remaining_amount'),
      amount_in_words: valOrDash('amount_in_words'),
      payment_methods: paymentDetailsArr,
      payment_summary: paymentSummary,
      file: fileName,
      description: valOrDash('description'),
      prepared_by: valOrDash('prepared_by'),
      approved_by: valOrDash('approved_by'),
      received_by: valOrDash('received_by'),
      isEditing: false
    };
    vouchersData.push(voucherObj);
    renderVouchersTable();

    // Reset form and UI
    form.reset();
    document.getElementById('successAlert').classList.remove('d-none');
    setTimeout(() => {
      document.getElementById('successAlert').classList.add('d-none');
    }, 2500);
    // Remove all dynamic payment blocks except the first
    document.querySelectorAll('.payment-block').forEach(block => block.remove());
    updateAmountReceived();
    calculateRemaining();
  });

  function renderVouchersTable() {
    const table = document.getElementById('voucherListTable').querySelector('tbody');
    table.innerHTML = '';
    vouchersData.forEach((v, idx) => {
      const row = document.createElement('tr');
      if (v.isEditing) {
        // Editable row
        row.innerHTML = `
          <td>${idx + 1}</td>
          <td><input class='form-control form-control-sm' value="${v.voucher_no}"></td>
          <td><input class='form-control form-control-sm' type='date' value="${v.date}"></td>
          <td><input class='form-control form-control-sm' value="${v.expense_type}"></td>
          <td><input class='form-control form-control-sm' value="${v.paid_to}"></td>
          <td><input class='form-control form-control-sm' type='number' value="${v.total_amount}"></td>
          <td><input class='form-control form-control-sm' type='number' value="${v.amount_received}"></td>
          <td><input class='form-control form-control-sm' type='number' value="${v.pending_amount}"></td>
          <td><input class='form-control form-control-sm' type='number' value="${v.remaining_amount}"></td>
          <td><input class='form-control form-control-sm' value="${v.amount_in_words}"></td>
          <td><textarea class='form-control form-control-sm' rows='2'>${v.payment_summary.replace(/<br>/g, '\n').replace(/<hr[^>]*>/g, '')}</textarea></td>
          <td>${v.file}</td>
          <td><textarea class='form-control form-control-sm' rows='2'>${v.description}</textarea></td>
          <td><input class='form-control form-control-sm' value="${v.prepared_by}"></td>
          <td><input class='form-control form-control-sm' value="${v.approved_by}"></td>
          <td><input class='form-control form-control-sm' value="${v.received_by}"></td>
          <td>
            <button class='btn btn-sm btn-success' onclick='saveVoucherRow(${idx})'>Save</button>
            <button class='btn btn-sm btn-secondary' onclick='cancelEditVoucherRow(${idx})'>Cancel</button>
          </td>
        `;
      } else {
        row.innerHTML = `
          <td>${idx + 1}</td>
          <td>${v.voucher_no}</td>
          <td>${v.date}</td>
          <td>${v.expense_type}</td>
          <td>${v.paid_to}</td>
          <td>${v.total_amount}</td>
          <td>${v.amount_received}</td>
          <td>${v.pending_amount}</td>
          <td>${v.remaining_amount}</td>
          <td>${v.amount_in_words}</td>
          <td>${v.payment_summary}</td>
          <td>${v.file}</td>
          <td>${v.description}</td>
          <td>${v.prepared_by}</td>
          <td>${v.approved_by}</td>
          <td>${v.received_by}</td>
          <td><button class='btn btn-sm btn-primary' onclick='editVoucherRow(${idx})'>Edit</button></td>
        `;
      }
      table.appendChild(row);
    });
  }

  // Make edit/save/cancel globally accessible
  window.editVoucherRow = function(idx) {
    vouchersData[idx].isEditing = true;
    renderVouchersTable();
  }
  window.cancelEditVoucherRow = function(idx) {
    vouchersData[idx].isEditing = false;
    renderVouchersTable();
  }
  window.saveVoucherRow = function(idx) {
    const table = document.getElementById('voucherListTable').querySelector('tbody');
    const row = table.rows[idx];
    const inputs = row.querySelectorAll('input, textarea');
    // Map inputs to voucher fields
    let i = 0;
    vouchersData[idx].voucher_no = inputs[i++].value;
    vouchersData[idx].date = inputs[i++].value;
    vouchersData[idx].expense_type = inputs[i++].value;
    vouchersData[idx].paid_to = inputs[i++].value;
    vouchersData[idx].total_amount = inputs[i++].value;
    vouchersData[idx].amount_received = inputs[i++].value;
    vouchersData[idx].pending_amount = inputs[i++].value;
    vouchersData[idx].remaining_amount = inputs[i++].value;
    vouchersData[idx].amount_in_words = inputs[i++].value;
    vouchersData[idx].payment_summary = inputs[i++].value.replace(/\n/g, '<br>');
    // file is not editable
    vouchersData[idx].description = inputs[i++].value;
    vouchersData[idx].prepared_by = inputs[i++].value;
    vouchersData[idx].approved_by = inputs[i++].value;
    vouchersData[idx].received_by = inputs[i++].value;
    vouchersData[idx].isEditing = false;
    renderVouchersTable();
  }

  // Final Submit All button
  document.getElementById('finalSubmitBtn').addEventListener('click', function() {
    // For now, just show all data as JSON
    alert('Final Submit!\n' + JSON.stringify(vouchersData, null, 2));
  });
</script>

</body>
</html>
