<?php
 // Authentication callback
 function maika_api_authenticate_request($request) {
    return true;
    // // Lấy secret key từ request headers hoặc query parameters
    // $provided_key = $request->get_header('Maika-Secret-Key');
    
    // // Nếu không tìm thấy trong headers, thử lấy từ query parameters
    // if (!$provided_key) {
    //     $provided_key = $request->get_param('maika_secret_key');
    // }

    // // Key bảo mật được lưu trữ trước (giả sử bạn lưu secret key trong cấu hình plugin)
    // $stored_secret_key = get_option("maika_ssid") ? get_option("maika_ssid") : '0aqUFmkNZH7foculbkeGbp5MhPaMuv1M'; // Thay bằng secret key thực tế của bạn

    // // Kiểm tra nếu secret key đúng
    // if ($provided_key && $provided_key === $stored_secret_key) {
    //     return true; // Xác thực thành công
    // }

    // // Trả về lỗi nếu secret key sai
    // return new WP_Error(
    //     'rest_forbidden',
    //     __('Invalid secret key.'),
    //     array('status' => 403)
    // );
 }