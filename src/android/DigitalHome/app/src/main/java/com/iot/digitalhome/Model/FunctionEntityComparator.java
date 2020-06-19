package com.iot.digitalhome.Model;

import java.util.Comparator;

public class FunctionEntityComparator implements Comparator<FunctionEntity> {

    @Override
    public int compare(FunctionEntity lhs, FunctionEntity rhs) {
        return rhs.getFunctionID() - lhs.getFunctionID();
    }
}
