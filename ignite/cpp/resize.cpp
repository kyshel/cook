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
		"{@resize_width  |      |  }"
		"{@resize_height |      |   }"
		;

	CommandLineParser parser(argc, argv, keys);
	if (parser.has("help"))
	{
		parser.printMessage();
		return 0;
	}
	if (!parser.check())
	{
		parser.printErrors();
		return 0;
	}

	String src_path = parser.get<String>(0);
	String dst_path = parser.get<String>(1);
	int resize_width = parser.get<int>(2);
	int resize_height = parser.get<int>(3);

	Mat src_image;
	Mat dst_image;
	src_image = imread(src_path, 1);

	if ( !src_image.data)
	{
		printf(" No image data \n ");
		return -1;
	}


	cvtColor(src_image, dst_image, CV_BGR2GRAY);
	resize(src_image, dst_image, Size(resize_width, resize_height));

	message = imwrite(dst_path, dst_image) ? "OK" : "FAIL";
	cout << message;

	return 0;
}
