document.addEventListener('DOMContentLoaded', () => {
    var homediv = document.getElementById('home');
    var productbuydiv = document.getElementById('product-buy');
    var exportedgoodsdiv = document.getElementById('exported-goods');
    var ourbrachesdiv = document.getElementById('our-branches');
    var probalitydiv = document.getElementById('probality');
    var settingdiv = document.getElementById('setting-dashboard');
    var profilediv = document.getElementById('container-dashboard-profile');

    // buttons
    document.getElementById('button-home').addEventListener('click', () => {
        homediv.style.display = 'block';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-product-buy').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'block';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-exported-goods').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'block';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-our-branches').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'block';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-probality').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'block';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-setting-dashboard').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'block';
        profilediv.style.display = 'none';
    });

    document.getElementById('button-profile').addEventListener('click', () => {
        homediv.style.display = 'none';
        productbuydiv.style.display = 'none';
        exportedgoodsdiv.style.display = 'none';
        ourbrachesdiv.style.display = 'none';
        probalitydiv.style.display = 'none';
        settingdiv.style.display = 'none';
        profilediv.style.display = 'block';
    });
});
