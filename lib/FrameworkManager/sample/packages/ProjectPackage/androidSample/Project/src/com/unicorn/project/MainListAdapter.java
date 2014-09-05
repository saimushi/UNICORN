package com.unicorn.project;

import java.util.ArrayList;
import java.util.List;

import com.unicorn.model.ModelBase;
import com.unicorn.model.UserModel;
import com.unicorn.utilities.LoadBitmapManager;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class MainListAdapter extends ArrayAdapter<ModelBase> {
	private Context mContext;
	private List<ModelBase> mList;
	private LayoutInflater mInflater;

	public MainListAdapter(Context context, int textViewResourceId, List<ModelBase> list) {
		super(context, textViewResourceId, list);

		mContext = context;
		mList = list;
		mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	// 1要素分のビューの取得
	@Override
	public View getView(final int position, View convertView, final ViewGroup parent) {
		View view;
		UserModel model = (UserModel)mList.get(position);

		view = mInflater.inflate(R.layout.main_list_row, null);

		ImageView image = (ImageView)view.findViewById(R.id.main_list_image);
		TextView name = (TextView)view.findViewById(R.id.main_list_name);
		
		LoadBitmapManager.doDownloadBitmap(mContext, image, null, model.profile_image_url, true, 0);
		
		name.setText(model.name);
		
		return view;
	}
}
