$(document).ready(function()
{
    $("#submit").click(function()
    {
        var first = $("input[name='first']").val();
        var second = $("input[name='second']").val();
        var third = $("input[name='third']").val();
        var forth = $("input[name='forth']").val();
        $("#r1").html("");
        $("#r2").html("");
        $("#r3").html("");
        $("#r4").html("");
        var reg = /^([0-9]|(1[0-3]))$/;
        if(reg.test(first) && reg.test(second) && reg.test(third) && reg.test(forth))
        {
            var data = "first=" + first + "&second=" + second + "&third=" + third + "&forth=" + forth;
            console.log(data);
            $.ajax({
                method: "POST",
                url: "24Points.php",
                dataType: "JSON",
                data: data
            })
            .done(function(data) {
                $("#result").html(data.msg);
            })
            .fail(function(data) {
                console.log(data);
            })
        }
        else
        {
            if(reg.test(first) == false)
            {
                $("#r1").html("请输入0-13的正整数");
            }
            if(reg.test(second) == false)
            {
                $("#r2").html("请输入0-13的正整数");
            }
            if(reg.test(third) == false)
            {
                $("#r3").html("请输入0-13的正整数");
            }
            if(reg.test(forth) == false)
            {
                $("#r4").html("请输入0-13的正整数");
            }
        }
        
    })
});
