
$(document).ready(function(){
    $("#updateAdminButton").click(function(event){
        let isCorrect = 1;
        let message="";
        let firstName = $.trim($("#firstName").val());
        let lastName = $.trim($("#lastName").val());
        let password = $.trim($("#password").val());
        let repeatPassword = $.trim($("#repeatPassword").val());
        let cnic = $.trim($("#cnic").val());
        let phone = $.trim($("#phone").val());
        let passwordLength = $("#password").val().length;
        let cnicLength = $("#cnic").val().length;
        let phoneLength = $("#phone").val().length;
        //alert(email);
        if (firstName  === '') {
            isCorrect = 0;
            message = 'First Name is empty.';
        } else if (lastName  === '') {
            isCorrect = 0;
            message = 'Last Name is empty.';
        } else if (password  !== '') {
            if(passwordLength<8){
                isCorrect = 0;
                message = 'Password must be 8 characters long.';
            }	else if (password  !== repeatPassword) {
                isCorrect = 0;
                message = 'Password fields are not match.';
            }
        } else if (cnic  === '') {
            isCorrect = 0;
            message = 'CNIC is empty.';
        } else if (phone  === '') {
            isCorrect = 0;
            message = 'Phone Number is empty.';
        } else if(!$.isNumeric(cnic)){
            isCorrect = 0;
            message = 'CNIC must be a numeric.';
        } else if(cnicLength!==13){
            isCorrect = 0;
            message = 'CNIC length must be equal to 13 digits.';
        } else if(!$.isNumeric(phone)){
            isCorrect = 0;
            message = 'Phone Number must be a numeric.';
        } else if (phoneLength  !== 11) {
            isCorrect = 0;
            message = 'Phone Number length must be equal to 11 digits.';
            return false;
        }
        if (isCorrect) {
            $("#updateProfileForm").submit();
        } else {
            alert(message);
            event.preventDefault();
        }
    });

    setTimeout(function() {
        $('#adminProfileMsg').show("slow").fadeOut('slow');
    }, 2000);

    $("#updateAccount").click(function(event){
        let isCorrect = 1;
        let message="";
        let id = $.trim($("#id").val());
        let firstName = $.trim($("#firstName").val());
        let lastName = $.trim($("#lastName").val());
        let cnic = $.trim($("#cnic").val());
        let phone = $.trim($("#phone").val());
        let cnicLength = $("#cnic").val().length;
        let phoneLength = $("#phone").val().length;
        //alert(email);
        if (firstName  === '') {
            isCorrect = 0;
            message = 'First Name is empty.';
        } else if (lastName  === '') {
            isCorrect = 0;
            message = 'Last Name is empty.';
        } else if (cnic  === '') {
            isCorrect = 0;
            message = 'CNIC is empty.';
        } else if (phone  === '') {
            isCorrect = 0;
            message = 'Phone Number is empty.';
        } else if(!$.isNumeric(cnic)){
            isCorrect = 0;
            message = 'CNIC must be a numeric.';
        } else if(cnicLength!==13){
            isCorrect = 0;
            message = 'CNIC length must be equal to 13 digits.';
        } else if(!$.isNumeric(phone)){
            isCorrect = 0;
            message = 'Phone Number must be a numeric.';
        } else if (phoneLength  !== 11) {
            isCorrect = 0;
            message = 'Phone Number length must be equal to 11 digits.';
        }
        if (isCorrect) {
            updateAccount(id, firstName, lastName, cnic, phone);
        } else {
            alert(message);
        }
    });

    $(document).on('click','a[data-role=update]',function () {
        let id = $(this).data('id');
        let firstName = $('#row'+id).children('td[data-target=firstName]').text();
        let lastName = $('#row'+id).children('td[data-target=lastName]').text();
        let cnic = $('#row'+id).children('td[data-target=cnic]').text();
        let phone = $('#row'+id).children('td[data-target=phone]').text();

        $('#id').val(id);
        $('#firstName').val(firstName);
        $('#lastName').val(lastName);
        $('#cnic').val(cnic);
        $('#phone').val(phone);

        $('#modalUpdateAccount').modal('toggle');
    });

    $('#addBookButton').click(function () {
        $('#bookModalTitle').text('Add a new book');
        $('#submitBookButton').text('Add');
        $('#bookModal').find("input,textarea,select").val('');
        $('#bookModal').modal('toggle');
    });

    $(document).on('click', 'a[data-role=updateBook]', function () {
        let id = $(this).data('bookid');
        let bookName = $('#bookRow'+id).children('td[data-target=bookName]').text();
        let rackNumber = $('#bookRow'+id).children('td[data-target=rackNumber]').text();
        let authorName = $('#bookRow'+id).children('td[data-target=authorName]').text();
        let price = $('#bookRow'+id).children('td[data-target=price]').text();
        let purchaseDate = $('#bookRow'+id).children('td[data-target=purchaseDate]').text();

        $('#bookId').val(id);
        $('#bookName').val(bookName);
        $('#rackNumber').val(rackNumber);
        $('#authorName').val(authorName);
        $('#bookPrice').val(price);
        $('#purchaseDate').val(purchaseDate);

        $('#bookModalTitle').text('Update book');
        $('#submitBookButton').text('Update');
        $('#bookModal').modal('toggle');
    });

    $(".issueBookRequest").click(function(){
        let id = $(this).data('bookid');
        let name = $(this).data('bookname');
        $('#requestModalTitle').text('Request for Issue '+name+' Book');
        $('#bookIssueId').val(id);
        $('#bookIssueName').val(name)
        $('#issueBookModal').modal('toggle');
    });

    $("#issueRequestButton").click(function(){
        let id = $.trim($("#bookIssueId").val());
        let name = $.trim($("#bookIssueName").val());

        $('#issueBookModal').modal('toggle');
        $('#requestLoader').show();
        $.ajax({
            url: '/lms/config/action.php',
            type: 'POST',
            dataType: 'JSON',
            data: { issueRequest: "Issue Request", bookIssueId: id, bookIssueName: name},
            success: function (data) {
                $('#requestLoader').hide();
                $("#messageForBook").text(data.statusMessage);
                $("#messageForBook").show("slow").delay(3000).fadeOut("slow");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#requestLoader').hide();
                $("#messageForBook").text('Status: '+textStatus+'   Error: '+errorThrown);
                $("#messageForBook").show("slow").delay(5000).fadeOut("slow");
            }
        });
    });

    $("#submitBookButton").click(function(){
        let isCorrect = 1;
        let message="";
        let id = $.trim($("#bookId").val());
        let bookName = $.trim($("#bookName").val());
        let rackNumber = $.trim($("#rackNumber").val());
        let authorName = $.trim($("#authorName").val());
        let bookPrice = $.trim($("#bookPrice").val());
        let purchaseDate = $.trim($("#purchaseDate").val());
        if (bookName  === '') {
            isCorrect = 0;
            message = 'Book Name is empty.';
        } else if (rackNumber  === '') {
            isCorrect = 0;
            message = 'Rack Number is empty.';
        } else if (authorName  === '') {
            isCorrect = 0;
            message = 'Author Name is empty.';
        } else if (bookPrice  === '') {
            isCorrect = 0;
            message = 'Price is empty.';
        } else if (purchaseDate === '') {
            isCorrect = 0;
            message = 'Purchase Date is empty.';
        } else if(!$.isNumeric(bookPrice)){
            isCorrect = 0;
            message = 'Price must be a numeric.';
        } else if (bookPrice === '0') {
            isCorrect = 0;
            message = 'Please enter a price greater than 0.';
        }
        if (isCorrect) {
            if(id !== ''){
                updateBook(id, bookName, rackNumber, authorName, bookPrice, purchaseDate);
            }
            else{
                addBook(bookName, rackNumber, authorName, bookPrice, purchaseDate);
            }
        } else {
            alert(message);
        }
    });

    $(document).on('click', 'button[data-role=approveBookRequest]', function () {
        let id = $(this).data('requestid');
        let studentId = $(this).data('requeststudentid');
        let bookId = $(this).data('requestbookid');
        approveBookRequest(id, studentId, bookId);
    });

});

function collectBook(id, studentId, bookId) {
    $('#approveOrRejectLoader').show();
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            collectId: id, studentId: studentId, bookId: bookId
        },
        success: function (data) {
            $('#approveOrRejectLoader').hide();
            if(data.status) {
                $('#requestRow'+id).children('td[data-target=status]').text('Approved and Collected');
                $('#collectButton'+id).prop('disabled', true);
            }
            $("#messageForRequestsBook"+id).text(data.statusMessage);
            $("#messageForRequestsBook"+id).show("slow").delay(2000).fadeOut("slow");

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#approveOrRejectLoader').hide();
            $("#messageForRequestsBook"+id).text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForRequestsBook"+id).show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function approveBookRequest(id, studentId, bookId) {
    $('#approveOrRejectLoader').show();
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            approveRequestId: id, studentId: studentId, bookId: bookId
        },
        success: function (data) {
            $('#approveOrRejectLoader').hide();
            if(data.status) {
                $('#requestRow'+id).children('td[data-target=status]').text('Approved');
                $('#approveButton'+id).prop('disabled', true);
                $('#rejectButton'+id).prop('disabled', true);
                $('#collectButton'+id).prop('disabled', false);
            }
            $("#messageForRequestsBook"+id).text(data.statusMessage);
            $("#messageForRequestsBook"+id).show("slow").delay(2000).fadeOut("slow");

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#approveOrRejectLoader').hide();
            $("#messageForRequestsBook"+id).text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForRequestsBook"+id).show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function rejectBookRequest(id, studentId, bookId) {
    $('#approveOrRejectLoader').show();
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            rejectRequestId: id, studentId: studentId, bookId: bookId
        },
        success: function (data) {
            $('#approveOrRejectLoader').hide();
            if(data.status) {
                $('#requestRow'+id).children('td[data-target=status]').text('Rejected');
                $('#approveButton'+id).prop('disabled', true);
                $('#rejectButton'+id).prop('disabled', true);
            }
            $("#messageForRequestsBook"+id).text(data.statusMessage);
            $("#messageForRequestsBook"+id).show("slow").delay(2000).fadeOut("slow");

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#approveOrRejectLoader').hide();
            $("#messageForRequestsBook"+id).text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForRequestsBook"+id).show("slow").delay(5000).fadeOut("slow");
        }
    });

}

function deleteAccount(id) {
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: { deleteId: id },
        success: function (data) {
            if(data.status) {
                $("#row" + id).hide();
            }
            $("#messageForAccount").text(data.statusMessage);
            $("#messageForAccount").show("slow").delay(3000).fadeOut("slow");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#messageForAccount").text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForAccount").show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function updateAccount(id, firstName, lastName, cnic, phone) {
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            updateId: id, firstName: firstName, lastName: lastName, cnic: cnic, phone: phone
        },
        success: function (data) {
            if(data.status) {
                $('#row'+id).children('td[data-target=firstName]').text(firstName);
                $('#row'+id).children('td[data-target=lastName]').text(lastName);
                $('#row'+id).children('td[data-target=cnic]').text(cnic);
                $('#row'+id).children('td[data-target=phone]').text(phone);
            }
            $("#messageForAccount").text(data.statusMessage);
            $('#modalUpdateAccount').modal('toggle');
            $("#messageForAccount").show("slow").delay(3000).fadeOut("slow");

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#messageForAccount").text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForAccount").show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function addBook(bookName, rackNumber, authorName, bookPrice, purchaseDate) {
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: { newBookName: bookName, newRackNumber: rackNumber, newAuthorName: authorName, newBookPrice: bookPrice, newPurchaseDate: purchaseDate
        },
        success: function (data) {
            if(data.status){
                let newBookData = '<tr id="bookRow'+data.id+'">';
                newBookData += '<td data-target="bookName">'+bookName+'</td>'+'<td data-target="rackNumber">'+rackNumber+'</td>'+'<td data-target="authorName">'+authorName+'</td>'+
                    '<td data-target="price">'+bookPrice+'</td>'+'<td data-target="purchaseDate">'+purchaseDate+'</td>'+'<td>'+data.add+'</td>'+'<td><a class="btn btn-outline-primary" ' +
                    'data-role="updateBook" data-bookid="'+data.id+'">Update</a>' + ' ' +
                    '<a class="btn btn-outline-danger" onclick="deleteBook(\'' + data.id + '\')">Delete</a></td></tr>';
                $('#bookRow').append(newBookData);
            }
            $("#messageForBook").text(data.statusMessage);
            $('#bookModal').modal('toggle');
            $("#messageForBook").show("slow").delay(3000).fadeOut("slow");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#messageForBook").text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForBook").show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function deleteBook(id) {
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: { deleteBookId: id },
        success: function (data) {
            if(data.status) {
                $("#bookRow" + id).hide();
            }
            $("#messageForBook").text(data.statusMessage);
            $("#messageForBook").show("slow").delay(3000).fadeOut("slow");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#messageForBook").text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForBook").show("slow").delay(5000).fadeOut("slow");
        }
    });
}

function updateBook(id, bookName, rackNumber, authorName, bookPrice, purchaseDate) {
    $.ajax({
        url: '/lms/config/action.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            updateBookId: id, updateBookName: bookName, updateRackNumber: rackNumber, updateAuthorName: authorName, updateBookPrice: bookPrice, updatePurchaseDate: purchaseDate
        },
        success: function (data) {
            if(data.status) {
                $('#bookRow'+id).children('td[data-target=bookName]').text(bookName);
                $('#bookRow'+id).children('td[data-target=rackNumber]').text(rackNumber);
                $('#bookRow'+id).children('td[data-target=authorName]').text(authorName);
                $('#bookRow'+id).children('td[data-target=price]').text(bookPrice);
                $('#bookRow'+id).children('td[data-target=purchaseDate]').text(purchaseDate);
            }
            $("#messageForBook").text(data.statusMessage);
            $('#bookModal').modal('toggle');
            $("#messageForBook").show("slow").delay(3000).fadeOut("slow");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#messageForBook").text('Status: '+textStatus+'   Error: '+errorThrown);
            $("#messageForBook").show("slow").delay(5000).fadeOut("slow");
        }
    });
}