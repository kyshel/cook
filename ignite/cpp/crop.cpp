#include <opencv2/opencv.hpp>
#include <iostream>

using namespace std;
using namespace cv;

int main(int argc, char** argv)
{
	String message;

	const String keys =
		"{help h usage ? |      | print help   }"
		"{@src_image     |      | src_image   }"
		"{@dst_image     |      | dst_image   }"
		"{@crop_x        |      |   }"
		"{@crop_y        |      |   }"
		"{@crop_width    |      |   }"
		"{@crop_height   |      |   }"
		;

	CommandLineParser parser(argc, argv, keys);
	if (parser.has("help"))
	{
		parser.printMessage();
		return 0;
	}
	
	// get val from arguments
	String src_path = parser.get<String>(0);
	String dst_path = parser.get<String>(1);
	int crop_x = parser.get<int>(2);
	int crop_y = parser.get<int>(3);
	int crop_width = parser.get<int>(4);
	int crop_height = parser.get<int>(5);

	// check is_argv_legal
	if (!parser.check())
	{
		parser.printErrors();
		return 0;
	}

	// load src_image
	Mat src_image;
	Mat dst_image;
	src_image = imread(src_path, 1);
	if ( !src_image.data)
	{
		printf(" No image data \n ");
		return -1;
	}

	// process
	Rect myROI(crop_x, crop_y, crop_width, crop_height);
	dst_image = src_image(myROI);

	// print
	message = imwrite(dst_path, dst_image) ? "OK" : "FAIL";
	cout << message;

	return 0;
}
