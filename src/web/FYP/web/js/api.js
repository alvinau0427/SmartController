var apiUrl = 'http://fypgroup4.ddns.net/FYP/api/';

$(document).ready(function() {
    getJson(apiUrl + 'functions/', showLocations);
});

function showLocations(e) {
    var val = e.data;
    var isChecked;
    var list = $('#alternative');
    list.html('');
    for (var i = 0; i < val.length; i++) {
        var n = val[i];
        list.append(
            '<div class="col-md-3">' +
            '<div class="price-blocks">' +
            '<h3>' + n.Name + '</h3>' +
            '<div class="price-round">' + 
            '<div class="price-tariff">' + n.FunctionID + '</div>' +
            '<div class="price-period">' + n.Location + '</div>' +
            '</div>' +
            '<table id="' + n.FunctionID + '" class="table table-condensed"></table>' +
            '<div class="price-apply-button-block">' +
            '<button class="btn btn-large">Apply Now</button>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        console.log('[LOADING]--------------show: location------------');
        console.log('[LOADING]: (' + n.FunctionID + ') ' + n.Name);
    }
    console.log('[DONE]----------------list: Location-------------');

    for (var i = 0; i < val.length; i++) {
        getJson(apiUrl + 'modules/' + val[i].FunctionID, showModules);
    }
    for (var i = 0; i < val.length; i++) {
        getJson(apiUrl + 'sensors/' + val[i].FunctionID, showSensors);
    }
}

function showModules(e) {
    var val = e.data;
    var isChecked;
    var isOn;
    var id = '#' + val[0].FunctionID;
    var list = $(id);
    console.log(list);
    // list.html('');
    for (var i = 0; i < val.length; i++) {
        var n = val[i];
        isChecked = (val[i].Status == true) ? 'checked' : '';
        isOn = (val[i].Status == true) ? 'On' : 'Off';
        list.append(
            '<tr>' +
            '<td>' +
            '<strong>' + n.Name + ': ' + isOn + '</strong><input type="checkbox" onChange="updateStatus(' + val[i].DeviceID + ', ' + val[i].Pin + ', ' + val[i].Status + ')" ' + isChecked + '/></td>' +
            '</td>' +
            '</tr>'
        );
        console.log('[LOADING]--------------show: modules------------');
        console.log('[LOADING]: (' + n.Status + ') ' + n.Name);
    }
    console.log('[DONE]----------------list: Modules-------------');
}
// <div class="orb-form"><label class="toggle" for="iduk"><input name="uk" checked="" id="iduk" type="checkbox"><i></i></label></div>

function showSensors(e) {
    var val = e.data;
    // list.html('');
    var id = '#' + val[0].FunctionID;
    var list = $(id);
    for (var i = 0; i < val.length; i++) {
        var n = val[i];
        list.append(
            '<tr>' +
            '<td>' +
            '<span>' + n.Name + ': ' + n.Value + '</span>' +
            '</td>' +
            '</tr>'
        );
        console.log('[LOADING]--------------show: sensors------------');
        console.log('[LOADING]: (' + n.Value + ') ' + n.Name);
    }
    console.log('[OK]----------------list: Sensors-------------');
}

function getJson(url, func) {
    $.ajax({
        url: url,
        type: 'GET',
        contentType: 'application/json',
        dataType: 'json',
        error: function() {
            console.log('Error: ');
        },
        success: function(e) {
           func(e);
        }
    });
}



function updateStatus(id, pin, status) {
    var newStatus = status ? 0 : 1;
    console.log('deviceID: ' + id + ', pin: ' + pin + ', status: ' + newStatus);
    $.ajax({
        url: 'http://fypgroup4.ddns.net/FYP/api/modules',
        type: 'PUT',
        data: {
            "deviceID": id,
            "pin": pin,
            "status": newStatus,
        },
        dataType: 'json',
        // crossDomain: true,
        // jsonpCallback: 'getFunction',
        // jsonp: false,
        // contentType: 'application/json',
        success: function(e) {
            console.log('updateStatus success, status: ' + newStatus);
            getJson(apiUrl + 'functions/', showLocations);
        },
        error: function(e) {
            console.log(e);
        }
    });
}


// function showLocations(e) {
//     var val = e.data;
//     var isChecked;
//     for (var i = 0; i < val.length; i++) {
//         var t = document.createTextNode('Status');
//         var status = document.createTextNode(val[i].status);
//         var span = document.createElement('span');
//         span.setAttribute('class', 'price-value');
//         span.appendChild(t);
//         var div_tariff = document.createElement('div');
//         div_tariff.setAttritue('class', 'price-tariff');
//         table.html('');
//     }
//     console.log('[OK]----------------list: functions-------------');
//     var table = jQuery('<table/>', {
//         'class': 'table table-condensed'
//     });
//     table.html('');
//     for (var i = 0; i < val.length; i++) {
//         var text = document.createTextNode(val[i].Name);
//         var strong = document.createElement('strong').appendChild(text);
//         var td = document.createElement('td').appendChild(strong);
//         var tr = document.createElement('tr').appendChild(td);
//         table.append(tr);

//         // isChecked = (val[i].Status == true) ? 'checked' : '';
//         // console.log('modules: ' + val[i].Name + ' ' + isChecked);
//         // list.append(
//         //     '<tr>' +
//         //     '<td>' + val[i].DeviceID + '</td>' +
//         //     '<td>' + val[i].Pin + '</td>' +
//         //     '<td>' + val[i].Name + '</td>' +
//         //     '<td>' + val[i].Type + '</td>' +
//         //     '<td>' + val[i].TypeName + '</td>' +
//         //     '<td>' + val[i].Status + '</td>' +
//         //     '<td>' + val[i].Function + '</td>' +
//         //     '<td><input type="checkbox" onChange="updateStatus(' + val[i].DeviceID + ', ' + val[i].Pin + ', ' + val[i].Status + ')" ' + isChecked + '/></td>' +
//         //     '</tr>'
//         // );
//     }
//     console.log('[OK]----------------list: Modules-------------');
//     console.log(table);

//     for (var i = 0; i < val.length; i++) {
//         var text = document.createTextNode(val[i].Name);
//         var td = document.createElement('td').appendChild(text);
//         var tr = document.createElement('tr').appendChild(td);
//         table.append(tr);

//         // isChecked = (val[i].Status == true) ? 'checked' : '';
//         // console.log('modules: ' + val[i].Name + ' ' + isChecked);
//         // list.append(
//         //     '<tr>' +
//         //     '<td>' + val[i].DeviceID + '</td>' +
//         //     '<td>' + val[i].Pin + '</td>' +
//         //     '<td>' + val[i].Name + '</td>' +
//         //     '<td>' + val[i].Type + '</td>' +
//         //     '<td>' + val[i].TypeName + '</td>' +
//         //     '<td>' + val[i].Status + '</td>' +
//         //     '<td>' + val[i].Function + '</td>' +
//         //     '<td><input type="checkbox" onChange="updateStatus(' + val[i].DeviceID + ', ' + val[i].Pin + ', ' + val[i].Status + ')" ' + isChecked + '/></td>' +
//         //     '</tr>'
//         // );
//     }
//     console.log('[OK]----------------list: Modules-------------');
//     console.log(table);

// }