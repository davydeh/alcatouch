/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

(function($) {

    /*var types = {
        manhole: 1,
        outfall: 2,
        conduit: 3
    };

    $('#send', '#main').click(function(e) {
        e.preventDefault();

        var jsonData = {
            name: "project 1",
            scale: "#000",
            dx: 2,
            dy: 3,
            limitMinX: 1,
            limitMaxX: 1,
            limitMinY: 1,
            limitMaxY: 1,
            arrayElement: {
                conduit1: {
                    name: "conduit 1",
                    color: "#000",
                    type: types.conduit,
                    manning: 1,
                    length: 5.1036,
                    diameter: 0,
                    startNode: {
                        type: types.manhole,
                        nodeId: 1,
                        name: "manhole 1",
                        color: "#000",
                        x: -1.7679,
                        y: 1.6429,
                        r: 8,
                        inflow: 0,
                        elevGround: 0,
                        elevInvert: 0
                    },
                    endNode: {
                        type: types.manhole,
                        nodeId: 2,
                        name: "manhole 2",
                        color: "#000",
                        x: -2,
                        y: 2,
                        r: 2,
                        inflow: 0,
                        elevGround: 0,
                        elevInvert: 0
                    }
                },
                conduit2: {
                    name: "conduit 2",
                    color: "#000",
                    type: types.conduit,
                    manning: 1,
                    length: 5.1036,
                    diameter: 0,
                    startNode: {
                        type: types.manhole,
                        nodeId: 2,
                        name: "manhole 2",
                        color: "#000",
                        x: -2,
                        y: 2,
                        r: 2,
                        inflow: 0,
                        elevGround: 0,
                        elevInvert: 0
                    },
                    endNode: {
                        type: types.outfall,
                        nodeId: 3,
                        name: "outfall 3",
                        outfallType: "Odd Outfall",
                        color: "#000",
                        x: -1.6964,
                        y: 2.1143,
                        r: 8,
                        inflow: 0,
                        elevGround: 0,
                        elevInvert: 0
                    }
                },
                node1: {
                    type: types.manhole,
                    nodeId: 4,
                    name: "manhole 3",
                    color: "#000",
                    x: -1.6964,
                    y: 2.1143,
                    r: 8,
                    inflow: 0,
                    elevGround: 0,
                    elevInvert: 0
                }
            }
        };
        var userData = {
            username: 'pepe',
            password: 'a'
        };

        $.ajax({
            type: "POST",
            url: "/sftext/web/app_dev.php/save",
            dataType: "json",
            data: {
                project: jsonData,
                user: userData
            },
            success: function(data) {
                alert(data);
            }
        });

    });

    $('#load', '#main').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "/sftext/web/app_dev.php/load",
            dataType: "json",
            data: {
                project: { id : '22' },
                user: { username : 'davyd', password: 'a' }
            },
            success: function(data) {
                alert(data);
            }
        });
    });
    
    $('#auth', '#main').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "/sftext/web/app_dev.php/logincheck",
            dataType: "json",
            data: {
                user: { username : 'davyd', password: 'a' }
            },
            success: function(data) {
                alert(data.success);
            }
        });
    });
    
    $('#list', '#main').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "/sftext/web/app_dev.php/projects",
            dataType: "json",
            data: {
                user: { username : 'davyd' }
            },
            success: function(data) {
                alert(data.success);
            }
        });
    });
    
    $('#list1', '#main').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "/sftext/web/app_dev.php/projects",
            dataType: "json",
            data: {
                user: { username : 'pepe' }
            },
            success: function(data) {
                alert(data.success);
            }
        });
    });
/*

    /*************************************
     *  Funcionalidades LISTAR PROYECTOS 
     *************************************/
    
    $('li', '#projects-list').hover(function(evt){
        $(this).find('.delete').show();
    }, function(evt){
        $(this).find('.delete').hide();
    });
    
    /*************************************
     *         DESCARGAR PROYECTO
     *************************************/
    
    $('#projects-list').find('li .delete').click(function(evt){
        
        var selected = $(this).parent().attr('id');
        $('.project').val(selected);
                
        $(this).parents('form').submit();
    });
    
})(jQuery);