<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

function createButton($type, $data, $label, $icon, $extraClass = '')
{
    // Menentukan warna berdasarkan jenis tombol
    switch ($type) {
        case 'edit':
            $class = 'btn-warning';
            $iconClass = 'far fa-edit';
            break;
        case 'delete':
            $class = 'btn-danger';
            $iconClass = 'fas fa-trash';
            break;
        case 'detail':
            $class = 'btn-info';
            $iconClass = 'fas fa-info-circle';
            break;
        default:
            $class = 'btn-secondary'; // Default button style
            $iconClass = 'fas fa-question-circle';
            break;
    }

    // Menambahkan kelas CSS untuk memastikan panjang tombol sama
    return '<button class="btn me-2 btn ' . $class . ' text-white ' . $extraClass . ' btn-same-width" ' . $data . '>
                <i class="' . $iconClass . '"></i> ' . $label . '
            </button>';
}
