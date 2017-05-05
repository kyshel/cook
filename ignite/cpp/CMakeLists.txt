cmake_minimum_required(VERSION 2.8)
project (alpha)


file(GLOB cpp_files  *.cpp)

find_package(OpenCV REQUIRED)
SET(OPENCV_CPP_SAMPLES_REQUIRED_DEPS opencv_core opencv_imgproc opencv_flann
	opencv_imgcodecs opencv_videoio opencv_highgui opencv_ml opencv_video
	opencv_objdetect opencv_photo opencv_features2d opencv_calib3d
	opencv_stitching opencv_videostab opencv_shape ${OPENCV_MODULES_PUBLIC} ${OpenCV_LIB_COMPONENTS})

ocv_check_dependencies(${OPENCV_CPP_SAMPLES_REQUIRED_DEPS})
ocv_include_directories("${OpenCV_SOURCE_DIR}/include")#for opencv.hpp
ocv_include_modules_recurse(${OPENCV_CPP_SAMPLES_REQUIRED_DEPS})




MACRO(OPENCV_DEFINE_CPP_EXAMPLE name srcs)
	#set(the_target "lala_${name}")
	set(the_target "${name}")
	add_executable(${the_target} ${srcs})
	ocv_target_link_libraries(${the_target} ${OPENCV_LINKER_LIBS} ${OPENCV_CPP_SAMPLES_REQUIRED_DEPS})
ENDMACRO()

# make output into a 'bin' dir
set(CMAKE_RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/bin)

foreach(sample_filename ${cpp_files})
	get_filename_component(sample ${sample_filename} NAME_WE)
	OPENCV_DEFINE_CPP_EXAMPLE(${sample}  ${sample_filename})
endforeach()