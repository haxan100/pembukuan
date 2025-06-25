<?php defined('BASEPATH') or exit('No direct script access allowed');

class Slider extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('SliderModel');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('button');
		
	}

	public function index()
	{
		$data['sliders'] = $this->SliderModel->getAllSliders();
		$this->load->view('admin/slider_view', $data);
	}

	public function getSliders()
	{
		$sliders = $this->SliderModel->getAllSliders();
		$data = [];
		foreach ($sliders as $index => $slider) {
			$editButton = createButton('edit', 'data-id="' . $slider->id . '" data-image="' . base_url('uploads/sliders/') . $slider->image . '" data-caption="' . $slider->caption . '"', 'Edit', 'far fa-edit','btn-edit');
			$deleteButton = createButton('delete', 'data-id="' . $slider->id . '" data-image-name="' . $slider->image . '"', 'Hapus', 'fas fa-trash','btn-delete');

			$data[] = [
				$index + 1,
				'<img src="' . base_url('uploads/sliders/') . $slider->image . '" class="img-thumbnail slider-thumbnail" data-src="' . base_url('uploads/sliders/') . $slider->image . '" style="cursor: pointer; width: 80px;">',
				$slider->caption,
				$editButton . $deleteButton
			];
		}

		echo json_encode([
			"draw" => intval($this->input->post("draw")),
			"recordsTotal" => count($sliders),
			"recordsFiltered" => count($sliders),
			"data" => $data
		]);
	}


	public function upload()
	{
		$config['upload_path']   = './uploads/sliders/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size']      = 2048;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('image')) {
			echo json_encode(["status" => "error", "message" => $this->upload->display_errors()]);
		} else {
			$fileData = $this->upload->data();
			$caption  = $this->input->post('caption');

			$sliderData = [
				'image'   => $fileData['file_name'],
				'caption' => $caption
			];

			$this->SliderModel->addSlider($sliderData);
			echo json_encode(["status" => "success", "message" => "Slider berhasil ditambahkan!"]);
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$imageName = $this->input->post('image_name');

		if ($this->SliderModel->deleteSlider($id)) {
			$imagePath = './uploads/sliders/' . $imageName;
			if (file_exists($imagePath)) {
				unlink($imagePath);
			}
			echo json_encode(["status" => "success", "message" => "Slider berhasil dihapus!"]);
		} else {
			echo json_encode(["status" => "error", "message" => "Gagal menghapus slider!"]);
		}
	}
	public function edit()
	{
		$id = $this->input->post('id');
		$caption = $this->input->post('caption');
		$slider = $this->SliderModel->getSliderById($id);

		if ($_FILES['image']['name']) {
			$config['upload_path']   = './uploads/sliders/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']      = 2048;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('image')) {
				echo json_encode(["status" => "error", "message" => $this->upload->display_errors()]);
				return;
			} else {
				$fileData = $this->upload->data();
				$newImage = $fileData['file_name'];

				$oldImagePath = './uploads/sliders/' . $slider->image;
				if (file_exists($oldImagePath)) {
					unlink($oldImagePath);
				}
				$updateData['image'] = $newImage;
			}
		}

		$updateData['caption'] = $caption;
		$this->SliderModel->updateSlider($id, $updateData);

		echo json_encode(["status" => "success", "message" => "Slider berhasil diperbarui!"]);
	}
}
