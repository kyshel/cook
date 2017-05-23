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
		"{@flip_code     |      |              }"
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
	int flip_code = parser.get<int>(2);


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
	if (!src_image.data)
	{
		printf(" No image data \n ");
		return -1;
	}

	// process
	flip_code = (flip_code == 2) ? -1 : flip_code;
	flip(src_image, dst_image, flip_code);

	// print
	message = imwrite(dst_path, dst_image) ? "OK" : "FAIL";
	cout << message;

	return 0;
}
