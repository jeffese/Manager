/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function fulscreen(typ){
    if (typ == 1) {
        $('[tag=norm_screen]').show();
        $('[tag=full_screen]').hide();
        $('[tag=norm__screen]').show();
        $('[tag=full__screen]').hide();
        top.bodyFrame.cols='0,*';
    } else {
        top.bodyFrame.cols='0,*';
        $('[tag=norm_screen]').hide();
        $('[tag=full_screen]').show();
        $('[tag=norm__screen]').hide();
        $('[tag=full__screen]').show();
        top.bodyFrame.cols='160,*';
    }
}

