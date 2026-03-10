'use strict';

/* eslint-disable require-jsdoc, no-unused-vars */

var CalendarList = [];

function CalendarInfo() {
    this.id = null;
    this.name = null;
    this.checked = true;
    this.color = null;
    this.bgColor = null;
    this.borderColor = null;
    this.dragBgColor = null;
}

function addCalendar(calendar) {
    CalendarList.push(calendar);
}

function findCalendar(id) {
    var found;

    CalendarList.forEach(function (calendar) {
        if (calendar.id === id) {
            found = calendar;
        }
    });

    return found || CalendarList[0];
}

function hexToRGBA(hex) {
    var radix = 16;
    var r = parseInt(hex.slice(1, 3), radix),
        g = parseInt(hex.slice(3, 5), radix),
        b = parseInt(hex.slice(5, 7), radix),
        a = parseInt(hex.slice(7, 9), radix) / 255 || 1;
    var rgba = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

    return rgba;
}

(function () {
    var calendar;
    var id = 0;

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'My Calendar';
    calendar.color = '#ffffff';
    calendar.bgColor = '#AA2224';
    calendar.dragBgColor = '#AA2224';
    calendar.borderColor = '#AA2224';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Company';
    calendar.color = '#ffffff';
    calendar.bgColor = '#0D6EFD';
    calendar.dragBgColor = '#0D6EFD';
    calendar.borderColor = '#0D6EFD';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Family';
    calendar.color = '#ffffff';
    calendar.bgColor = '#ff5583';
    calendar.dragBgColor = '#ff5583';
    calendar.borderColor = '#ff5583';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Friend';
    calendar.color = '#ffffff';
    calendar.bgColor = '#03bd9e';
    calendar.dragBgColor = '#03bd9e';
    calendar.borderColor = '#03bd9e';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Travel';
    calendar.color = '#ffffff';
    calendar.bgColor = '#16A34A';
    calendar.dragBgColor = '#16A34A';
    calendar.borderColor = '#16A34A';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'etc';
    calendar.color = '#ffffff';
    calendar.bgColor = '#9d9d9d';
    calendar.dragBgColor = '#9d9d9d';
    calendar.borderColor = '#9d9d9d';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Birthdays';
    calendar.color = '#ffffff';
    calendar.bgColor = '#e2c636';
    calendar.dragBgColor = '#e2c636';
    calendar.borderColor = '#e2c636';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'National Holidays';
    calendar.color = '#ffffff';
    calendar.bgColor = '#DC2626 ';
    calendar.dragBgColor = '#DC2626 ';
    calendar.borderColor = '#DC2626 ';
    addCalendar(calendar);
})();
