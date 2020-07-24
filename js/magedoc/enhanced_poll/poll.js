var MageDocPolls = function(container, polls) {
    this.container = $(container);
    this.polls = {};

    var rows = this.container.select('tbody tr');
    var rowspan = 0;
    var shift = 0;
    var rowspans = [];
    for (var index = 0; index < rows.length; index++) {
        var row = rows[index];
        console.log(row);
        console.log(rowspans);
        console.log('shift='+shift);

        for (i = 0; i < polls.length; i++) {
            var poll = polls[i];
            this.polls[poll.poll_id] = poll;


            //var first = row.select('td:first-child')[0];
            var value = row.select('td:nth-child(' + (poll.value_column - shift) + ')')[0];

            if (typeof value != 'undefined') {
                this.addInput(row, value, poll, shift);
            }
        }

        for (j = rowspans.length-1; j >= 0; j--) {
            if (rowspans[j] == 1) {
                rowspans.splice(j, 1);
                shift -= 1;
            } else {
                rowspans[j] -= 1;
            }
        }
        row.select('td').each(function(first){
            if (first.getAttribute('rowspan')-1 > 0){
                var rowspan = first.getAttribute('rowspan');
                rowspans.push(rowspan-1);
                shift += 1;
            }
        });
    }
};

MageDocPolls.prototype = {
    answerChangeHandler: function(event, url) {
        console.log('answerChangeHandler');
        var input = event.target;
        new Ajax.Request(
            url,
            {
                'method': 'post',
                'parameters': {
                    'vote': input.getValue()
                }
            }
        );
    },

    addInput: function(row, value, poll, shift) {
        var pollId = poll.poll_id;
        var inputType = typeof poll.poll_input_type != 'undefined'
            ? poll.poll_input_type
            : 'text';
        var customer = row.select('td:nth-child(' + (poll.customer_id_column - shift) + ')')[0];
        var url = '/xamin/enhancedPoll_vote/add/poll_id/' + pollId + '/customer_id/' + customer.innerHTML
        if (inputType == 'text') {
            var input = new Element('input',
                {
                    'type': 'text',
                    'value': value.innerHTML
                });
        } else if (inputType == 'range') {
            var input = new Element('select');
            var range = poll.poll_input_range.split('-');
            var text = 'Неизвестно';
            var options = {'value': '', 'title': text};
            var option = new Element('option', options).update(text);
            input.append(option);
            for (r = range[0]; r <= range[1]; r++) {
                var options = {'value': r, 'title': r};
                var option = new Element('option', options).update(r);
                if (value.innerHTML.length && value.innerHTML == r) {
                    option.setAttribute('selected', 'selected');
                }
                input.append(option);
            }
        } else if (inputType == 'yes_no') {
            var input = new Element('select');
            var text = 'Неизвестно';
            var options = {'value': '', 'title': text};
            var option = new Element('option', options).update(text);
            input.append(option);
            var text = 'Да';
            var options = {'value': 1, 'title': text};
            var option = new Element('option', options).update(text);
            if (value.innerHTML == 1) {
                option.setAttribute('selected', 'selected');
            }
            input.append(option);
            var text = 'Нет';
            var options = {'value': 0, 'title': text};
            var option = new Element('option', options).update(text);
            if (value.innerHTML.length && value.innerHTML == 0) {
                option.setAttribute('selected', 'selected');
            }
            input.append(option);
        }
        value.update(input);
        Event.observe(input, 'change', this.answerChangeHandler.bindAsEventListener(this, url))
        console.log(url);
    }
}