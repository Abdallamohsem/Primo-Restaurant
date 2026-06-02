function validateAndSubmit() {
    const name = document.getElementsByName('customer_name')[0].value.trim();
    const phone = document.getElementsByName('phone')[0].value.trim();
    const addr = document.getElementsByName('address')[0].value.trim();
    
    if(!name || !phone || !addr) {
        Swal.fire({ icon: 'warning', title: 'بيانات ناقصة', text: 'يرجى إكمال بيانات التوصيل' });
        return;
    }

    Swal.fire({
        title: 'جاري تنفيذ طلبك...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    document.getElementById('orderForm').submit();
}

