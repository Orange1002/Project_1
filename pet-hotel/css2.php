<style>
    tr:hover {
        background-color: #e6f0fa;
        /* 懸停淺藍色 */
        transition: background-color 0.3s;
    }

    img {
        /* max-width: 80px; */
        height: auto;
        border-radius: 4px;
        transition: transform 0.3s;
    }

    img:hover {
        transform: scale(1.1);
        /* 懸停放大效果 */
    }
</style>
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
    }

    .table th,
    .table td {
        padding: 12px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #f1f3f5;
    }

    .img-thumbnail {
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .text-success {
        color: #28a745 !important;
    }

    .fw-bold {
        font-weight: bold;
    }

    .btn-group .btn {
        margin-right: 5px;
    }


    /* 自定義按鈕顏色（紅色，仿 Airbnb） */
    .btn-custom {
        background-color: rgba(62, 191, 237, 0.96);
        border-color: rgba(62, 191, 237, 0.96);
        color: #fff;
        padding: 8px 16px;
        border-radius: 5px;
    }

    .btn-custom:hover {
        background-color: rgba(245, 160, 23, 0.919);
        border-color: rgba(245, 160, 23, 0.919);
        color: #fff;
        text-decoration: none;
    }
</style>