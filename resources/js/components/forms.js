import $ from 'jquery';

$(function () {
    const $startGameForm = $('#startGameForm');
    const $guessNumberForm = $('#guessNumberForm');
    const $quitGameForm = $('#quitGameForm');
    const $failureGameMessage = $('.failure-game-message');
    const $successGameMessage = $('.success-game-message')
    const $resultTable = $('#result-table');
    const $resultList = $('#result-table tbody');

    $guessNumberForm.hide();
    $quitGameForm.hide();
    $failureGameMessage.hide();
    $successGameMessage.hide();
    $resultTable.hide();

    $startGameForm.submit(function (e) {
        e.preventDefault();
        $startGameForm.hide();
        $guessNumberForm.show();
        $quitGameForm.show();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize()
        });
    });

    $guessNumberForm.submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function (response) {
                $resultTable.show();

                const $newRow = $('<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">')
                    .appendTo($resultList);

                $('<td class="px-6 py-4">').text(response.result.guessNumber).appendTo($newRow)
                $('<td class="px-6 py-4">').text(response.result.bulls).appendTo($newRow)
                $('<td class="px-6 py-4">').text(response.result.cows).appendTo($newRow)

                if (response.successGameMessage) {
                    $successGameMessage.show().
                    html('<p class="font-bold uppercase text-center">' + response.successGameMessage + '</p>');
                    setTimeout(() => {
                        $successGameMessage.empty().hide();
                        $resultList.empty().hide();
                        $guessNumberForm.hide();
                        $quitGameForm.hide();
                        $startGameForm.show();
                    }, 1000);
                }
            }
        });

        $(this).find('input[name="guessNumber"]').val('');
    });

    $quitGameForm.submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function (response) {
                $failureGameMessage.show().
                html('<p class="font-bold uppercase text-center">' + response.failureGameMessage + '</p>');
                setTimeout(() => {
                    $failureGameMessage.empty().hide();
                    $resultList.empty().hide();
                    $guessNumberForm.hide();
                    $quitGameForm.hide();
                    $startGameForm.show();
                }, 1000);
            }
        });
    });
});
